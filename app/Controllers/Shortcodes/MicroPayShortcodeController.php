<?php
namespace MicroPay\Controllers\Shortcodes;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\BillingFox\BillingFoxAPI;

class MicroPayShortcodeController extends BaseShortcodeController
{
	const KEY = 'paywall';

	public static $name = 'micropay';

	public static $description = 'Restricts website content';

	public static $args = [
		'price:req',
	];

	public $assets = [
		'js' => ['app.js'],
	];

	public function function( $attrs, $content = '' )
	{
		$attrs = shortcode_atts( [
			'price' => isset( $attrs['price'] ) ? $attrs['price'] : null,
			'uid' => $this->uniqueShortcodeID( $content ),
		], $attrs, self::$name );

		return $this->validateAttributes( $content, $attrs );
	}

	private function uniqueShortcodeID( $content )
	{
		$uID = md5( $content );
		update_post_meta( get_the_ID(), MP_SHORTCODE_UID . $uID , $uID );

		return $uID;
	}

	public function unlock()
	{
		$wallData = mp_get_session( self::KEY );

		$api = new BillingFoxAPI;
		$api->validate( $wallData );
	}
}