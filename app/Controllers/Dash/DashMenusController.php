<?php
namespace MicroPay\Controllers\Dash;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\Support\Blueprints\HookableInterface;
use MicroPay\Controllers\Dash\Menus\BaseMenuController;

class DashMenusController implements HookableInterface
{
	public static $menus = [
		Menus\MicroPayMenuController::class,
		Menus\OverviewMenuController::class,
		Menus\SettingsMenuController::class,
		Menus\APISettingsMenuController::class,
		Menus\WCSettingsMenuController::class,
	];

	public function init()
	{
		foreach ( self::$menus as $menu ) :
			if ( is_subclass_of( $menu, BaseMenuController::class ) ) ( new $menu )->load();
		endforeach;
	}

	public function hook()
	{
		add_action( 'admin_menu', [$this, 'init'] );
	}
}