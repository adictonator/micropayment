<?php
namespace MPEngine\BillingFox;

defined( 'ABSPATH' ) or die( 'Not allowed!' );
/** @todo complete reworking */
abstract class BillingFoxUserController
{
	public function login()
	{

	}

	public function register( \WP_User $user )
	{
		$bfUserID = $this->generateBillingFoxUserID( $user->ID );
		update_user_meta( $user->ID, BF_UID, $bfUserID );
		$this->setBillingFoxUser();
		if ( $user->ID > 0 && ! $this->isAuthUser() ) :
		endif;
	}

	public function isAuthUser()
	{
		if ( is_user_logged_in() ) :
			$user = wp_get_current_user();

			return get_user_meta( $user->ID, BF_UID, true );
		endif;

		return;
	}

	private function generateBillingFoxUserID( int $userID )
	{
		return substr(str_shuffle( md5( $userID . microtime() ) ), 0, 11 );
	}

	private function setBillingFoxUser()
	{
		return static::init()->postRequest( 'identify', ['user' => '$id', 'email' => '$email'] );
	}
}