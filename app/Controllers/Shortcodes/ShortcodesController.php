<?php
namespace MicroPay\Controllers\Shortcodes;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\Support\Blueprints\HookableInterface;

class ShortcodesController implements HookableInterface
{
	public static $shortcodes = [
		MicroPayShortcodeController::class,
		ProfileShortcodeController::class,
		TransactionsShortcodeController::class,
	];

	public function init()
	{
		foreach ( self::$shortcodes as $shortcode ) :
			if ( is_subclass_of( $shortcode, BaseShortcodeController::class ) ) ( new $shortcode )->load();
		endforeach;
	}

	public function hook()
	{
		$this->init();
	}
}