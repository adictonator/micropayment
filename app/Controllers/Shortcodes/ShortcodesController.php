<?php
namespace MicroPay\Controllers\Shortcodes;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\Support\Blueprints\HookableInterface;

class ShortcodesController implements HookableInterface
{
	protected $shortcodes = [
		MicroPayShortcodeController::class,
	];

	public function init()
	{
		foreach ( $this->shortcodes as $shortcode ) :
			if ( is_subclass_of( $shortcode, BaseShortcodeController::class ) ) ( new $shortcode )->load();
		endforeach;
	}

	public function hook()
	{
		$this->init();
	}
}