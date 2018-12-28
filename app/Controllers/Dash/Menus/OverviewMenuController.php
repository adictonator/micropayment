<?php
namespace MicroPay\Controllers\Dash\Menus;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

class OverviewMenuController extends BaseMenuController
{
	const TITLE = 'Overview';

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
		$menus = [
			'general' => SettingsMenuController::TITLE,
			'api' 	  => APISettingsMenuController::TITLE,
			'woo' 	  => WCSettingsMenuController::TITLE,
		];
		$menuData = get_option( MP_GENERAL_SETTINGS_KEY, [] );

		$this->setView( 'dash.overview.index', compact( 'menus', 'menuData' ) );
	}
}
