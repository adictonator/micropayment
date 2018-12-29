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
		$generalSettings = get_option( MP_GENERAL_SETTINGS_KEY, [] );
		$apiSettings = $generalSettings['api'];

		$this->setView( 'dash.api.index', compact( 'apiSettings' ) );
	}

	protected function update()
	{
		echo "<pre>";
		print_r($_POST);
		echo "</pre>";
		$menuData = get_option( MP_GENERAL_SETTINGS_KEY, [] );
	}
}
