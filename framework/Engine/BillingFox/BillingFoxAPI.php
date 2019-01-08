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
		$this->url = 'https://' . ($credentials->api->mode->value == 'yes' ? 'test' : 'live') . '.billingfox.com';
		$this->debug = $credentials->api->debug->value;

		return $this;
	}

	public function needWall()
	{
		$this->getRequest( 'identify' );
	}

	public function unlock()
	{
		$postData = mp_filter_form_data( $_POST );

		if ( ! BillingFoxUserController::isAuthUser() ) :
			return $this->processUnlocking();
		else:
			return BillingFoxUserController::register( wp_get_current_user() );
			// return $this->handleGuestUser();
			// login/signup popup
		endif;

		// $this->spendAPI( 'spend', array_filter([
        //     'user' => $postData['id'],
        //     'amount' => (float) $postData['amount'],
        //     'description' => $postData['description'],
        // ]));
	}

	private function handleGuestUser()
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
		echo "<pre>";
		print_r('sadom');
		echo "</pre>";
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

	private function getRequest($path)
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
		echo ';asdasd';
        if ( is_wp_error( $result ) ) {
            $this->logger('WP-ERROR: (%s) %s', [$result->get_error_message(), $result->get_error_code()]);
            $e = new BillingFoxAPIException($result->get_error_message());

            throw $e;
        }

        $body = @json_decode($result['body'], true);

        if (empty($body)) {
            $this->logger('ERROR: failed to decode response');

            $e = new BillingFoxAPIException(
                'failed to decode response'
            );

            $e->setResponse($result);

            throw $e;
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

		echo "<pre>";
		echo 'dsad';
		print_r($body);
		echo "</pre>";
        return $body;
	}

	private function getUniqueId($length = 13)
    {
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(ceil($length / 2));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(ceil($length / 2));
        } else {
            return uniqid();
        }
        return substr(bin2hex($bytes), 0, $length);
	}

	private function logger( $message, $args = [] )
    {
        if ( ! $this->debug ) return;

        array_unshift($args, "<p><code><mark>$message</mark></code></p>\n");

        echo call_user_func_array('sprintf', $args);
    }
}
