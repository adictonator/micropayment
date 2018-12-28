<?php
namespace MicroPay\Controllers\Dash\Menus;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MicroPay\Controllers\Shortcodes\ShortcodesController;

class SettingsMenuController extends BaseMenuController
{
	const TITLE = 'General Settings';

	public function __construct()
	{
		parent::__construct( $this->assets() );
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
		$shortcodes = ShortcodesController::$shortcodes;

		$this->setView( 'dash.settings.index', compact( 'shortcodes' ) );
	}
}
