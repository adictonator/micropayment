<?php
namespace MicroPay\Controllers\Shortcodes;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

class MicroPayShortcodeController extends BaseShortcodeController
{
	public static $name = 'micropay';

	public static $description = 'Restricts website content';

	public static $args = [
		'price:req',
		// 'description'
	];

	public $assets = [
		'js' => ['app.js'],
	];

	public function function( $attrs, $content = '' )
	{
		return $this->validateAttributes( $content, $attrs );
	}

	protected static function isAuthuser()
	{
		$authUser = wp_get_current_user();
		$bfUser = get_user_meta( 'billingfox_user', true );

		if ( ! $bfUser ) :
			// register user as billing fox user using '/identify' end
			return;
		endif;

		return $bfUser;
	}
}