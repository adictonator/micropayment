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
		$apiSettings = $generalSettings->api;

		$this->setView( 'dash.api.index', compact( 'apiSettings' ) );
	}

	public function update()
	{
		$data = mp_filter_form_data( $_POST );
		$menuData = get_option( MP_GENERAL_SETTINGS_KEY, false );

		if ( ! $menuData ) throw new \Exception('Malformed data!');

		foreach ( $menuData->api as $dKey => $val ) :
			$menuData->api->$dKey->value = $data[ $dKey ];
		endforeach;

		update_option( MP_GENERAL_SETTINGS_KEY, $menuData );

		$return['type'] = 'success';
		$return['msg'] = 'API Settings saved successfully!';

		echo json_encode( $return );
	}
}
