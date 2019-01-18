<?php
namespace MicroPay\Controllers\Dash\Menus;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MicroPay\Controllers\Dash\DashMenusController;

class MicroPayMenuController extends BaseMenuController
{
	const TITLE = '';

	public function __construct()
	{
		parent::__construct( 'main' );
	}

	public function view()
	{
		/** No need for a view here. */
	}
}
