<?php
namespace MicroPay\Controllers\Shortcodes;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

class ProfileShortcodeController extends BaseShortcodeController
{
	public static $name = 'micropay_profile';

	public static $description = 'Show Credits and E-Mail';

	public function function( $attrs, $content = '' )
	{
		return $this->validateAttributes( $content, $attrs );
	}

	protected function processShortcodeContent( $content, $attr )
	{
		# code...
	}
}