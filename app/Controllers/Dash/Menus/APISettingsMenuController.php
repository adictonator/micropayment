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
			$stripeSettings = $generalSettings->stripe;

			if ( empty( $stripeSettings->test->secret )
				|| empty( $stripeSettings->test->secret ) )
				$this->setNotice( 'warning', 'Please setup Stripe details!' );

			$this->setView( 'dash.api.index', compact( 'apiSettings', 'stripeSettings' ) );
		else:
			$this->setView( 'error.settings' );
		endif;
	}

	public function update()
	{
		$menuData = $this->getSettings();

		if ( ! $menuData ) throw new \Exception( 'Malformed data!' );

		! isset( $_POST['api']['debug'] ) ? $_POST['api']['debug'] = 'no' : '';

		$apiData = json_decode( json_encode( $_POST ) );
		$menuData->api = $apiData->api;
		$menuData->stripe = $apiData->stripe;

		if ( $this->setSettings( $menuData ) ) :
			$return['type'] = 'success';
			$return['msg'] = 'API Settings saved successfully!';

			echo json_encode( $return );
		endif;
	}
}
