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
		// 'description'
	];

	public $assets = [
		'js' => ['app.js'],
	];

	public function function( $attrs, $content = '' )
	{
		return $this->validateAttributes( $content, $attrs );
	}

	public function unlock()
	{
		$postData = mp_filter_form_data( $_POST );
		$wallData = mp_get_session( self::KEY );

		$api = new BillingFoxAPI;
		$api->validate( $wallData );
	}
}