<?php
namespace MicroPay\Controllers\Shortcodes;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

class TransactionsShortcodeController extends BaseShortcodeController
{
	public static $name = 'micropay_transactions';

	public static $description = 'List Transactions of logged in User';

	public static $args = [
		'email',
		'credits'
	];

	public function function( $attrs, $content = '' )
	{
		return $this->validateAttributes( $content, $attrs );
	}
}