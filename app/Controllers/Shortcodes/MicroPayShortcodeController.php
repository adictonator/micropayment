<?php
namespace MicroPay\Controllers\Shortcodes;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

class MicroPayShortcodeController extends BaseShortcodeController
{
	public static $name = 'micropay';

	public static $description = 'Restricts website content';

	public static $args = [
		'price:req',
	];

	public function function( $attrs, $content = '' )
	{
		return $this->validateAttributes( $content, $attrs );
	}
}