<?php
namespace MPEngine\BillingFox;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\Support\Traits\ResponseTrait;
use MicroPay\Controllers\Shortcodes\MicroPayShortcodeController;

/** @todo complete reworking */
abstract class BillingFoxUserController
{
	use ResponseTrait;

	public function login()
	{
		$user = wp_signon([
			'user_login' => $_POST['mp_user'],
			'user_password' => $_POST['mp_password'],
		]);

		if ( is_wp_error( $user ) ) $this->setResponse( $user->get_error_message() );

		wp_set_current_user( $user->ID );

		if ( ! $this->isAuthUser() ) :
			$this->httpCode = 401;
			$this->setResponse( 'Not a valid BillingFox user. Register <a href="#">here</a>' );
		else:
			$return['type'] = 'login';
			$return['user'] = mp_get_session( 'bfUser' );

			$this->setResponse( $return );
		endif;

		echo $this->response(1);
	}

	public function getBFUser()
	{
		if ( $bfUser = mp_get_session( 'bfUser' ) ) $this->setResponse( $bfUser );
		else $this->httpCode = 401;

		echo $this->response(1);
	}

	public function getSpends()
	{
		$user = mp_get_session( 'bfUser' );
		$spends = mp_get_session( 'spends' );
		$shortcodeID = mp_get_session( 'toUnlock' );

		if ( ! $spends ) {
			if ( $user ) $result = $this->spends( $user['key'] );

			if ( ! $result || $result['status'] !== 'success' ) :
				$this->httpCode = 403;
				$this->setResponse( 'User not in session!' );

				echo $this->response(1);
			endif;

			$spends = $result['spends'];
		}


		$result = MicroPayShortcodeController::processUnlockResponse( $spends );

		isset( $shortcodeID ) && ! empty( $shortcodeID ) ? $result['sid'] = $shortcodeID : '';

		mp_remove_session( 'toUnlock' );

		$this->setResponse( $result );

		echo $this->response(1);
	}

	/**
	 * Registers a WP user to BillingFox.
	 *
	 * @param \WP_User $user
	 * @return void
	 */
	public function register( \WP_User $user )
	{
		if ( $user->ID > 0 ) $bfUserID = $this->generateBillingFoxUserID( $user->ID );

		if ( $bfUserID ) $response = $this->setBillingFoxUser( $bfUserID, $user->user_email );

		if ( $response && $response['status'] === 'success' ) update_user_meta( $user->ID, BF_UID, $bfUserID );
	}

	/**
	 * Checks if logged in user is a registered BillingFox user.
	 *
	 * @return boolean
	 */
	public function isAuthUser()
	{
		if ( is_user_logged_in() ) :
			if ( ! empty( mp_get_session( 'bfUser' ) ) ) :
				return true;
				// $this->setResponse( $user );
			else :
				$user = wp_get_current_user();
				$bfUserID = $this->getUserBfID( $user->ID );

				if ( $bfUserID ) return $this->validateIdentity( $bfUserID );
				// else $this->httpCode = 401;
				else return false;
			endif;
		else:
			return false;
			// $this->httpCode = 401;
			// $this->setResponse( 'Not logged in.' );
		endif;

		// $this->response();
	}

	private function getUserBfID( int $userID )
	{
		return get_user_meta( $userID, BF_UID, true );
	}

	private function validateIdentity( string $bfUserID )
	{
		$result = $this->getRequest( 'identify?user=' . $bfUserID );

		if ( $result && $result['status'] === 'success' ) :
			mp_set_session( 'bfUser', $result['user'] );
			return true;
			// $this->setResponse( $result );
		else:
			// $this->httpCode = 401;
			// $this->setResponse( 'Not a BillingFox user.' );
			return false;
		endif;

		// $this->response();
	}

	/**
	 * Generates a random string based on logged in user ID.
	 *
	 * @param integer $userID
	 * @todo maybe don't use str_shuffle or implement existing ID checks.
	 * @return void
	 */
	private function generateBillingFoxUserID( int $userID )
	{
		return substr(str_shuffle( md5( $userID . microtime() ) ), 0, 13 );
	}

	/**
	 * Calls BillingFox to register a user.
	 *
	 * @param string $id
	 * @param string $email
	 * @return mixed|null
	 */
	private function setBillingFoxUser( string $id, string $email )
	{
		return $this->postRequest( 'identify', ['user' => $id, 'email' => $email] );
	}
}