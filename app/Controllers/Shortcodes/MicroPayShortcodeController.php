<?php
namespace MicroPay\Controllers\Shortcodes;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

class MicroPayShortcodeController extends BaseShortcodeController
{
	protected $name = 'micropay';

	public function function( $attrs, $content = '' )
	{
		return $this->validateAttributes( $content, $attrs );
	}
}