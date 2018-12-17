<?php
namespace MicroPay\Controllers\Dash;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\Support\ServiceProvider;
use MPEngine\Support\Blueprints\Hookable;
use MicroPay\Controllers\Dash\Menus\BaseMenuController;

class DashMenusController implements Hookable
{
	protected $menus = [
		Menus\MicroPayMenuController::class,
	];

	public function init()
	{
		foreach ( $this->menus as $menu ) :
			if ( is_subclass_of( $menu, BaseMenuController::class ) ) ( new $menu )->load();
		endforeach;	
	}

	public function hook()
	{
		add_action( 'admin_menu', [$this, 'init'] );
	}
}