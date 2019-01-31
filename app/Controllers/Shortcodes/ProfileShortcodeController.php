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

	public function recharge()
	{
		$bfUser = mp_get_session( 'bfUser' );

		if ( $bfUser ) return $this->api->recharge( $bfUser['key'] );
	}

	protected function processShortcodeContent( $content, $attr )
	{
		if ( mp_get_session( 'bfUser' ) || $this->api->isAuthUser() ) : return $this->getProfileContent( mp_get_session( 'bfUser' ) );
		else:
			$this->viewMessage = 'User not logged in!';

			return $this->getErrorContent();
		endif;
	}

	private function getProfileContent( $user )
	{
		ob_start();
		$this->setView( 'shortcode.profile', compact( 'user' ) );
		$errorContent = ob_get_contents();
		ob_end_clean();

		return $errorContent;
	}
}