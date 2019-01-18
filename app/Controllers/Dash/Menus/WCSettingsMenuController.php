<?php
namespace MicroPay\Controllers\Dash\Menus;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

class WCSettingsMenuController extends BaseMenuController
{
	const TITLE = 'WooCommerce Settings';

	public function view()
	{
		$this->setView( 'dash.wc.index' );
	}
}
