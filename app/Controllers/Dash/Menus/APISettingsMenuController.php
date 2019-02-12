<?php
namespace MicroPay\Controllers\Dash\Menus;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

class APISettingsMenuController extends BaseMenuController
{
	const TITLE = 'BillingFox API Settings';

	public $assets = [
		'js' => ['app.js'],
	];

	public function view()
	{
		$generalSettings = $this->getSettings();

		if ( true === $this->validateSettings( $generalSettings ) ) :
			$apiSettings = $generalSettings->api;

			if ( empty( $apiSettings->stripe->test->secret->value )
				|| empty( $apiSettings->stripe->test->secret->value ) )
				$this->setNotice( 'warning', 'Please setup Stripe details!' );

			$this->setView( 'dash.api.index', compact( 'apiSettings' ) );
		else:
			$this->setView( 'error.settings' );
		endif;
	}

	public function update()
	{
		$menuData = $this->getSettings();

		if ( ! $menuData ) throw new \Exception( 'Malformed data!' );

		foreach ( $menuData->api as $dKey => $val ) :
			$menuData->api->$dKey = $_POST[ $dKey ];
		endforeach;

		// $apiData = json_decode(json_encode($_POST));
		// $menuData->api = $apiData;
		echo "<pre>";
		print_r($_POST);
		print_r($menuData);
		echo "</pre>";
			die();
		if ( $this->setSettings( $menuData ) ) :
			$return['type'] = 'success';
			$return['msg'] = 'API Settings saved successfully!';

			echo json_encode( $return );
		endif;
	}
}
