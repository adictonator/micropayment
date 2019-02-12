<?php
namespace MicroPay\Controllers\Dash\Menus;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\Support\Traits\SettingsTrait;

class OverviewMenuController extends BaseMenuController
{
	const TITLE = 'Overview';

	public function __construct()
	{
		parent::__construct( 'sub', true );
	}

	public function view()
	{
		$menus = [
			'general' => SettingsMenuController::TITLE,
			'api' 	  => APISettingsMenuController::TITLE,
			'woo' 	  => WCSettingsMenuController::TITLE,
		];
		$menuData = $this->getSettings();
		if ( $this->validateSettings( $menuData ) ) $this->setView( 'dash.overview.index', compact( 'menus', 'menuData' ) );
		else $this->setView( 'error.settings' );
	}
}
