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
		if ( ! isset( $_POST['tokenID'] ) || empty( $_POST['tokenID'] ) || ! $bfUser = mp_get_session( 'bfUser' ) ) :
			$this->httpCode = 403;
			echo $this->response( 1 );
		endif;

		$rechargeData = [
			'user' => $bfUser['key'],
			'amount' => ( float ) $_POST['rechargeAmount'],
		];

		$response = $this->api->recharge( $rechargeData );

		if ( $response && $response['status'] === 'success' ) :
			$return['msg'] = $response['message'];
			mp_remove_session( 'bfUser' );
		else : $return['msg'] = 'Could not credit account!';
		endif;

		$this->setResponse( $return );
		echo $this->response( 1 );

	}

	protected function processShortcodeContent( $content, $attr )
	{
		if ( mp_get_session( 'bfUser' ) || $this->api->isAuthUser() ) : return $this->getProfileContent();
		else:
			$this->viewMessage = 'Get 50 credits upon signup!';

			return $this->getErrorContent( 'shortcode.profile-error' );
		endif;
	}

	public function getProfileContent()
	{
		$user =  mp_get_session( 'bfUser' );

		ob_start();
		$this->setView( 'shortcode.profile', compact( 'user' ) );
		$viewContent = ob_get_contents();
		ob_end_clean();

		if ( wp_doing_ajax() ) echo $viewContent;
		else return $viewContent;
	}

	public function processAuth()
	{
		$this->api->handleCurrentUser();
	}
}