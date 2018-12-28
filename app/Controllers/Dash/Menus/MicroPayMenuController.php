<?php
namespace MicroPay\Controllers\Dash\Menus;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MicroPay\Controllers\Dash\DashMenusController;

class MicroPayMenuController extends BaseMenuController
{
	const TITLE = '';

	public function __construct()
	{
		parent::__construct( $this->assets(), 'main' );
	}

	public function assets()
	{
		/** No need for assets as well. */
		return [];
	}

	public function view()
	{
		/** No need for a view here. */
	}
}
