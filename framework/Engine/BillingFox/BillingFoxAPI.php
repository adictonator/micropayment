<?php
namespace MPEngine\BillingFox;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\Support\Traits\ViewsTrait;
use MPEngine\Support\Exceptions\BillingFoxAPIException;

class BillingFoxAPI extends BillingFoxUserController
{
	use ViewsTrait;

	private $key;

	private $url;

	private $debug;

	public function init()
	{
		$credentials = get_option( MP_GENERAL_SETTINGS_KEY );

		/** @todo create an exception for this. since creds are expected at this point */
		if ( ! $credentials ) return;

		$this->key = $credentials->api->key->value;
		$this->url = 'https://' . ($credentials->api->mode->value == 'yes' ? 'test' : 'live') . '.billingfox.com/api';
		$this->debug = $credentials->api->debug->value;

		return $this;
	}

	public function needWall()
	{
		$this->getRequest( 'identify' );
	}

	public function validate( $wallData )
	{
		echo "<pre>";
		echo __CLASS__;
		var_dump($wallData);
		echo "</pre>";
		if ( $this->isAuthUser() ) :
			return $this->processUnlocking( $wallData );
		else:
			return $this->handleCurrentUser();
		endif;
	}

	// public function unlock()
	// {
	// 	$postData = mp_filter_form_data( $_POST );

	// 	$wallData = get_post_meta( $postData['pid'], MP_POST_WALL_KEY, true );

	// 	/** Don't do anything. */
	// 	if ( ! $wallData ) return;

	// 	if ( $this->isAuthUser() ) :
	// 		return $this->processUnlocking();
	// 	else:
	// 		return $this->handleCurrentUser();
	// 	endif;
	// }

	private function handleCurrentUser()
	{
		/** @todo after this, create functions for actually logging in user and checking for bf meta key again and/or registering them for bf and then setting their key for an account and then process the spend */
		ob_start();
		$this->setView( 'auth.authForms' );
		$return['html'] = ob_get_contents();
		ob_end_clean();

		echo json_encode( $return );
	}

	private function processUnlocking()
	{
		$this->postRequest( 'spend', array_filter([
		'user' => $postData['id'],
		'amount' => (float) $postData['amount'],
		'description' => $postData['description'],
		]));
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

	private function prepareResult( $result )
    {
        if ( is_wp_error( $result ) ) {
            $this->logger('WP-ERROR: (%s) %s', [$result->get_error_message(), $result->get_error_code()]);
            $e = new BillingFoxAPIException($result->get_error_message());

            throw $e;
        }

		$body = json_decode( $result['body'], true );

        if ( empty( $body ) ) {
            $this->logger( 'ERROR: failed to decode response' );

            $e = new BillingFoxAPIException(
                'failed to decode response'
			);

            // $e->setResponse($result);

            // throw $e;
        }

        if ($result['response']['code'] > 299) {
            $this->logger('ERROR: (%s) %s', [$result['response']['code'], $body['message']]);

            if ($result['response']['code'] == 402) {
                $e = new BillingFox_Api_InsufficientCoins(
                    $body['message'],
                    $result['response']['code']
                );

                $e->setInvoiceLink($body['link']);
            } else {
                $e = new BillingFoxAPIException(
                    $body['message'],
                    $result['response']['code']
                );
            }

            $e->setResponse($result);

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
