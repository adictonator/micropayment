<?php
namespace MPEngine\BillingFox;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\Support\Traits\ViewsTrait;
use MPEngine\Support\Traits\SettingsTrait;
use MPEngine\Support\Exceptions\BillingFoxAPIException;

class BillingFoxAPI extends BillingFoxUserController
{
	use ViewsTrait, SettingsTrait;


	private $key;

	private $url;

	private $debug;

	private $testMode;

	private $stripeTestKeys;

	private $stripeLiveKeys;

	public function __construct()
	{
		$credentials = $this->getSettings();

		if ( ! $this->validateSettings( $credentials ) ) return;

		$this->testMode = $credentials->api->testMode;
		$this->key = $credentials->api->key;
		$this->url = 'https://' . ($this->testMode === 'yes' ? 'test' : 'live') . '.billingfox.com/api';
		$this->debug = $credentials->api->debug;
		$this->stripeTestKeys = $credentials->stripe->test;
		$this->stripeLiveKeys = $credentials->stripe->live;
	}

	public function getStripeKeys()
	{
		$this->testMode === 'yes' ? $return['keys'] = $this->stripeTestKeys : $return['keys'] = $this->stripeLiveKeys;

		$this->setResponse( $return );
		echo $this->response( 1 );
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

			if ( $spends ) :
				foreach ( $spends as $spend ) :
					if ( mp_get_session( $spend['description'] ) ) :

						$return['shortcodeContent'][ $spend['description'] ] = mp_get_session( $spend['description'] );

					endif;
				endforeach;
			endif;

			$this->setResponse( $return );
			echo $this->response(1);
		else:
			return $this->handleCurrentUser();
		endif;
	}

	/**
	 * Displays login/registration form to the user.
	 *
	 * @return void
	 */
	public function handleCurrentUser()
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

		if ( $wallData && $wallData->status === 'locked' ) :

			$result = $this->spend( $billingFoxUser['key'], $wallData->attrs->price, $wallData->attrs->uid );

			if ( $result['status'] === 'success' ) :
				$this->setResponse( $result );
				mp_remove_session( 'spends' );
			else:
				$this->httpCode = 400;
				$this->setResponse( $result );
			endif;

		else:
			$this->httpCode = 400;
			$this->setResponse( 'The content is already unlocked!' );
		endif;

		echo $this->response(1);
	}

	/**
	 * Make the spend.
	 *
	 * @param string $userID
	 * @param integer $amount
	 * @param string $description
	 * @return array Response array
	 */
	public function spend( string $userID, int $amount, string $description = 'spend' )
	{
		return $this->postRequest( 'spend', array_filter( [
			'user' => $userID,
			'amount' => $amount,
			'description' => $description,
		] ) );
	}

	/**
	 * Recharge credits of the given BillingFox user.
	 *
	 * @param array $rechargeData
	 * @return array Response array
	 */
	public function recharge( array $rechargeData )
	{
		$data = [
			'user' => $rechargeData['user'],
			'amount' => $rechargeData['amount'],
			'description' => 'recharge',
		];

		return $this->postRequest( 'recharge', $data );
	}

	/**
	 * List all the spends of the user.
	 *
	 * @param string $userID
	 * @return array Response array
	 */
	public function spends( string $userID, $gte = null, $lte = null )
	{
		$params = build_query( array_filter( [
			'user' => $userID,
			'gte' => $gte,
			'lte' => $lte,
		] ) );

		return $this->getRequest( 'spend?'. $params );
	}

	/**
	 * Makes the POST call to the API endpoints.
	 *
	 * @param string $path
	 * @param array $payload
	 * @return array Response array
	 */
	protected function postRequest( string $path, array $payload = [] )
    {
		/** Log the response. */
		$this->logger( "POST: $this->url/$path " . json_encode( $payload ) );

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

	/**
	 * Makes the GET call to the API endpoints.
	 *
	 * @param string $path
	 * @return array Response array
	 */
	protected function getRequest( string $path )
    {
		/** Log the response. */
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
        if ( $this->debug !== 'yes' ) return;

        array_unshift($args, "<p><code><mark>$message</mark></code></p>\n");

        echo call_user_func_array('sprintf', $args);
    }
}
