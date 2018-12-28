<?php
namespace MicroPay\Controllers\Dash\Menus;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

class APISettingsMenuController extends BaseMenuController
{
	const TITLE = 'BillingFox API Settings';

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
		$this->setView( 'dash.api.index' );
	}

	protected function update()
	{
		$menuData = get_option( MP_GENERAL_SETTINGS_KEY, [] );
	}
}
