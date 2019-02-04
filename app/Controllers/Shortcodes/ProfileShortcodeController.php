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
		ob_start();
		$this->setView( 'shortcode.recharge' );
		$return['html'] = ob_get_contents();
		ob_end_clean();

		$return['type'] = 'recharge';
		$this->setResponse( $return );
		echo $this->response(1);
	}

	public function processRecharge()
	{
		if ( ! isset( $_POST['tokenID'] ) || empty( $_POST['tokenID'] ) || $bfUser = mp_get_session( 'bfUser' ) ) :
			$this->httpCode = 403;
			echo $this->response( 1 );
		endif;

		$rechargeData = [
			'user' => $bfUser,
			'amount' => $_POST['rechargeAmount'],
		];

		return $this->api->recharge( $rechargeData );
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