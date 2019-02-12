<?php
namespace MicroPay\Controllers\Dash\Menus;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MicroPay\Controllers\Shortcodes\ShortcodesController;

class SettingsMenuController extends BaseMenuController
{
	const TITLE = 'General Settings';

	public function view()
	{
		$shortcodes = ShortcodesController::$shortcodes;
		$menuData = $this->getSettings();

		if ( $this->validateSettings( $menuData ) ) $this->setView( 'dash.settings.index', compact( 'shortcodes' ) );
		else $this->setView( 'error.settings' );
	}
}
