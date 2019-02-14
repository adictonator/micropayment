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

	protected function processShortcodeContent( $content, $attrs )
	{
		$user = mp_get_session( 'bfUser' );
		$spends = mp_get_session( 'spends');

		if ( $user ) :
			if ( ! $spends ) :
				$spends = $this->api->spends( $user['key'] );

				if ( $spends['status'] === 'success' ) :
					mp_set_session( 'spends', $spends['spends'] );
				endif;
			endif;

			return $this->getSpendsContent();
		else:
			$this->viewMessage = 'Not a valid BillingFox user!';

			return $this->getErrorContent();
		endif;
	}

	public function getSpendsContent()
	{
		$spends = mp_get_session( 'spends' );

		ob_start();
		$this->setView( 'shortcode.spends', compact( 'spends' ) );
		$viewContent = ob_get_contents();
		ob_end_clean();

		if ( wp_doing_ajax() ) echo $viewContent;
		else return $viewContent;
	}
}