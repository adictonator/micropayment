<?php
namespace MPEngine\BillingFox;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\Support\Traits\ViewsTrait;
use MPEngine\Support\Traits\SettingsTrait;
use MPEngine\Support\Exceptions\BillingFoxAPIException;
use MicroPay\Controllers\Shortcodes\MicroPayShortcodeController;

class BillingFoxAPI extends BillingFoxUserController
{
	use ViewsTrait, SettingsTrait;

	private $key;

	private $url;

	private $debug;

	public function __construct()
	{
		$credentials = $this->getSettings();

		if ( ! $this->validateSettings( $credentials ) ) return;

		$this->key = $credentials->api->key->value;
		$this->url = 'https://' . ($credentials->api->mode->value == 'yes' ? 'test' : 'live') . '.billingfox.com/api';
		$this->debug = $credentials->api->debug->value;
	}

	public function getAPICred()
	{
		echo json_encode( [
			'key' => $this->key,
			'url' => $this->url,
			'debug' => $this->debug,
		] );
	}

	public function needWall( $sID )
	{
		$shortcodeContents = mp_get_session( $sID );

		if ( $shortcodeContents && isset( $shortcodeContents->status )
			&& $shortcodeContents->status === 'unlocked' ) return false;

		return true;
	}

	public function validate()
	{
		if ( $this->isAuthUser() ) :
			$spends = mp_get_session( 'spends' );

			$return['type'] = 'check-unlock';
			$return['data'] = $spends;

			$this->setResponse( $return );
			echo $this->response(1);
		else:
			return $this->handleCurrentUser();
		endif;
	}

	private function handleCurrentUser()
	{
		ob_start();
		$this->setView( 'auth.authForms' );
		$return['html'] = ob_get_contents();
		ob_end_clean();

		$return['type'] = 'auth';
		$this->setResponse( $return );
		echo $this->response(1);
	}

	public function processUnlocking()
	{
		$wallData = mp_get_session( $_POST['sid'] );
		$billingFoxUser = mp_get_session( 'bfUser' );

		$result = $this->postRequest( 'spend', array_filter([
			'user' => $billingFoxUser['key'],
			'amount' => (float) $wallData->attrs->price,
			'description' => $wallData->attrs->uid,
		]));

		if ( $result['status'] === 'success' ) :
			$shortcodeController = new MicroPayShortcodeController;
			$shortcodeController->unlockContent( $wallData->attrs->uid );
			$return = [
				'type' => 'unlock',
				'sid' => $wallData->attrs->uid,
				'content' => $wallData->content,
			];

			$this->setResponse( $return );
			mp_remove_session( 'spends' );
		else:
			$this->httpCode = 400;
		endif;

		echo $this->response(1);
	}

	public function spends( string $userID )
	{
		$params = build_query( array_filter( [
			'user' => $userID,
			'gte' => null,
			'lte' => null,
		] ) );

		return $this->getRequest( 'spend?'. $params );
	}

	protected function postRequest( $path, $payload = [] )
    {
		$this->logger('POST '.$this->url.$path.' '.json_encode($payload));

		$result = wp_remote_post( "$this->url/$path", [
				'headers' => [
                    'Authorization' => 'Bearer '. $this->key,
                    'Content-Type' => 'application/json; charset=UTF-8'
                ],
                'body' => json_encode( $payload ),
            ]
        );

        return $this->prepareResult( $result );
	}

	protected function getRequest($path)
    {
        $this->logger( 'GET ' . "$this->url/$path" );

        $result = wp_remote_get( "$this->url/$path", [
                'headers' => [
                    'Authorization' => 'Bearer '. $this->key,
                ],
            ]
        );

        return $this->prepareResult($result);
    }

	/**
	 * Not sure about this function. Will deprecate.
	 *
	 * @deprecated 1.0.0
	 * @param mixed $result
	 * @return void
	 */
	private function prepareResult( $result )
    {
        if ( is_wp_error( $result ) ) {
            $this->logger('WP-ERROR: (%s) %s', [$result->get_error_message(), $result->get_error_code()]);
            $e = new BillingFoxAPIException( $result->get_error_message() );
			echo $e->msg();
			return;
        }

		$body = json_decode( $result['body'], true );

        if ( empty( $body ) ) {
            $this->logger( 'ERROR: failed to decode response' );

            $e = new BillingFoxAPIException(
                'failed to decode response'
			);

            $e->msg($result);

            throw $e;
        }

        if ($result['response']['code'] > 299) {
            $this->logger('ERROR: (%s) %s', [$result['response']['code'], $body['message']]);

            if ($result['response']['code'] == 402) {
                $e = new BillingFoxAPIException(
                    $body['message'],
                    $result['response']['code']
                );

               $e->msg($body['link']);
            } else {
                $e = new BillingFoxAPIException(
                    $body['message'],
                    $result['response']['code']
                );
			}

            $e->msg($result);

            throw $e;
		}

        return $body;
	}

	/**
	 * Logger for API calls.
	 *
	 * @param string $message
	 * @param array $args
	 * @todo maybe separate it to a different class?
	 * @return void
	 */
	private function logger( $message, $args = [] )
    {
        if ( ! $this->debug ) return;

        array_unshift($args, "<p><code><mark>$message</mark></code></p>\n");

        echo call_user_func_array('sprintf', $args);
    }
}
