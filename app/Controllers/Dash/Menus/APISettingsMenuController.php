<?php
namespace MicroPay\Controllers\Dash\Menus;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\Support\Traits\SettingsTrait;

class APISettingsMenuController extends BaseMenuController
{
	use SettingsTrait;

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
		$generalSettings = $this->getSettings();

		if ( true === $this->validateSettings( $generalSettings ) ) :
			$apiSettings = $generalSettings->api;

			$this->setView( 'dash.api.index', compact( 'apiSettings' ) );
		else:
			$this->setView( 'error.settings' );
		endif;
	}

	public function update()
	{
		$menuData = $this->getSettings();

		if ( ! $menuData ) throw new \Exception('Malformed data!');

		foreach ( $menuData->api as $dKey => $val ) :
			$menuData->api->$dKey->value = $_POST[ $dKey ];
		endforeach;

		if ( $this->setSettings( $menuData ) ) :
			$return['type'] = 'success';
			$return['msg'] = 'API Settings saved successfully!';

			echo json_encode( $return );
		endif;

	}
}
