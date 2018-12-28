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

		// $menuData[ mp_menu_slug( self::$title ) ] = [
		// 	key( $_POST ) => [
		// 		'label' => 'API Mode',
		// 		'value' => $_POST['value'],
		// 	]
		// ];

		// $menuData = [
		// 	'micropay-billingfox-api-settings' => [
		// 		'mode' => [
		// 			'label' => 'API Mode',
		// 			'value' => 'test'
		// 		],
		// 		'debug' => [
		// 			'label' => 'API Debug Mode',
		// 			'value' => 'enabled'
		// 		]
		// 	]
		// ];
	}
}
