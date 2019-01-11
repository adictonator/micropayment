<?php
namespace MPEngine\BillingFox;

defined( 'ABSPATH' ) or die( 'Not allowed!' );
/** @todo complete reworking */
abstract class BillingFoxUserController
{
	public function login()
	{
		$postData = mp_filter_form_data( $_POST );
		echo "<pre>";
		echo 'losda';
		print_r($postData);
		echo "</pre>";
	}

	public function getSpends()
	{
		$user = mp_get_session( 'bfUser' );

		if ( $user ) :
			$params = build_query(array_filter([
				'user' => $user['user']['key'],
				'gte' => $gte?$gte->format('Y-m-d'):null,
				'lte' => $lte?$lte->format('Y-m-d'):null,
			]));
			$result = $this->getRequest( 'spend?'. $params );
		endif;
		echo "<pre>";
		print_r($result);
		echo "</pre>";
		if ( $result && $result['status'] === 'success' ) return $result;
		return;

	}

	/**
	 * Registers a WP user to BillingFox.
	 *
	 * @param \WP_User $user
	 * @return void
	 */
	protected function register( \WP_User $user )
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
	protected function isAuthUser()
	{
		if ( is_user_logged_in() ) :
			$user = wp_get_current_user();
			$bfUserID = get_user_meta( $user->ID, BF_UID, true );

			if ( $bfUserID ) return $this->validateIndentity( $bfUserID );
			else return;
		endif;

		return;
	}

	private function validateIndentity( string $bfUserID )
	{
		$result = $this->getRequest( 'identify?user=' . $bfUserID );
		if ( $result && $result['status'] === 'success' ) return mp_set_session( 'bfUser', $result );
		return;
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
	 * @return mixed|array|null
	 */
	private function setBillingFoxUser( string $id, string $email )
	{
		return $this->postRequest( 'identify', ['user' => $id, 'email' => $email] );
	}
}