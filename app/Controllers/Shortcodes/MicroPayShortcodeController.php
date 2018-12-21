<?php
namespace MicroPay\Controllers\Shortcodes;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

class MicroPayShortcodeController extends BaseShortcodeController
{
	protected static $name = 'micropay';

	public static function function( $attrs, $content = '' )
	{
		return parent::validateAttributes( $content, $attrs );
	}

	public function view()
	{
		return $this->setView( 'shortcode.wall' );
	}

	public static function loadView()
	{
		return ( new self )->view();
	}
}