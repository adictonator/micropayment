<?php
namespace MicroPay\Controllers\Dash\Menus;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MicroPay\Controllers\Dash\DashMenusController;

class OverviewMenuController extends BaseMenuController
{
	public static $title = 'Overview';

	public static $isMainMenu = true;

	public function __construct()
	{
		parent::__construct( $this->assets(), 'sub', true );
	}

	public function assets()
	{
		return [
			'css' => ['app.css'],
			'js' => [],
		];
	}

	public function view()
	{
		$menus = DashMenusController::$menus;
		$menuData = get_option( MP_GENERAL_SETTINGS_KEY, [] );

		$this->setView( 'dash.overview.index', compact( 'menus', 'menuData' ) );
	}
}
