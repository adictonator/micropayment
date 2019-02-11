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
		$this->setUserSession( $_POST['mp_user'], $_POST['mp_password'] );

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

	private function setUserSession( $userEmail, $userPassword )
	{
		$user = wp_signon( [
			'user_login' => $userEmail,
			'user_password' => $userPassword,
		] );

		if ( is_wp_error( $user ) ) :
			$this->setResponse( $user->get_error_message() );
			echo $this->response( 1 );
		endif;

		wp_set_current_user( $user->ID );
	}

	public function getBFUser()
	{
		if ( $bfUser = mp_get_session( 'bfUser' ) ) $this->setResponse( $bfUser );
		else $this->httpCode = 401;

		echo $this->response(1);
	}

	public function getSpends()
	{
		if ( ! $this->isAuthUser() ) :
			$this->httpCode = 401;
			echo $this->response( 1 );
		endif;

		$user = mp_get_session( 'bfUser' );
		$spends = mp_get_session( 'spends' );
		$shortcodeID = mp_get_session( 'toUnlock' );

		if ( ! $spends ) {
			if ( $user ) $result = $this->spends( $user['key'] );

			$spends = isset( $result['spends'] ) ? $result['spends'] : [];

			if ( ! $result || $result['status'] !== 'success' ) :
				$this->httpCode = 403;
				$this->setResponse( 'User not in session!' );

				echo $this->response(1);
			endif;
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
	public function register()
	{
		$return['type'] = 'register';
		if ( username_exists( $_POST['mp_user'] ) || email_exists( $_POST['mp_user'] ) ) :
			$return['msg'] = 'User email already exists!';
			$this->setResponse( $return );
			echo $this->response( 1 );
		endif;

		$userData = [
			'user_login' => $_POST['mp_user'],
			'user_email' => $_POST['mp_user'],
			'user_pass' => $_POST['mp_password'],
		];

		$userID = wp_insert_user( $userData );

		if ( is_wp_error( $userID ) ) :
			$return['msg'] = $userID->get_error_message();
			$this->setResponse( $return );
			echo $this->response( 1 );
		endif;

		$bfUserID = $this->generateBillingFoxUserID( $userID );
		$response = $this->setBillingFoxUser( $bfUserID, $_POST['mp_user'] );

		if ( $response && $response['status'] === 'success' ) :
			update_user_meta( $userID, BF_UID, $bfUserID );
			$this->setUserSession( $_POST['mp_user'], $_POST['mp_password'] );

			$return['bfUID'] = $bfUserID;
		else:
			$return['msg'] = 'Something went wrong!';
		endif;

		$this->setResponse( $return );

		echo $this->response( 1 );
	}

	/**
	 * Checks if logged in user is a registered BillingFox user.
	 *
	 * @return boolean
	 */
	public function isAuthUser()
	{
		if ( is_user_logged_in() ) :
			if ( ! empty( mp_get_session( 'bfUser' ) ) ) : return true;
			else :
				$user = wp_get_current_user();
				$bfUserID = self::getUserBfID( $user->ID );

				if ( $bfUserID ) return $this->validateIdentity( $bfUserID );
				else return false;
			endif;
		else : return false;
		endif;
	}

	/**
	 * Retrieves BillingFox ID of the user.
	 *
	 * @param integer $userID
	 * @return string|boolean BillingFox ID
	 */
	public static function getUserBfID( int $userID )
	{
		return get_user_meta( $userID, BF_UID, true );
	}

	// public function user()
	// {
	// 	$user = mp_get_session( 'bfUser' );

	// 	if ( ! $user ) $result = $this->getRequest( 'identify?user=' . $bfUserID );
	// }

	/**
	 * Checks if the id given is a valid BillingFox user.
	 *
	 * @param string $bfUserID
	 * @return boolean
	 */
	private function validateIdentity( string $bfUserID )
	{
		$result = $this->getRequest( 'identify?user=' . $bfUserID );

		if ( $result && $result['status'] === 'success' ) :
			mp_set_session( 'bfUser', $result['user'] );
			return true;
		else : return false;
		endif;
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
		return substr( str_shuffle( md5( $userID . microtime() ) ), 0, 13 );
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