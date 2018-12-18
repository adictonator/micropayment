<?php
namespace MicroPay\Controllers\Dash\Menus;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

class MicroPayMenuController extends BaseMenuController
{
	public $title = 'MicroPay';

	public function __construct()
	{
		parent::__construct( $this->assets(), 'main' );
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
		$this->setView( 'dash.micropay.index' );
	}
}
