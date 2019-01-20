<?php
namespace MicroPay\Controllers\Shortcodes;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\BillingFox\BillingFoxAPI;
use MPEngine\Support\Traits\ResponseTrait;

class MicroPayShortcodeController extends BaseShortcodeController
{
	use ResponseTrait;

	public static $name = 'micropay';

	public static $description = 'Restricts website content';

	public static $args = [
		'price:req',
	];

	public $assets = [
		'js' => ['app.js'],
	];

	public function function( $attrs, $content = '' )
	{
		$attrs = shortcode_atts( [
			'price' => isset( $attrs['price'] ) ? $attrs['price'] : null,
			'uid' => $this->uniqueShortcodeID( $content ),
		], $attrs, self::$name );

		return $this->validateAttributes( $content, $attrs );
	}

	private function uniqueShortcodeID( $content )
	{
		$uID = md5( $content );
		update_post_meta( get_the_ID(), MP_SHORTCODE_UID . $uID , $uID );

		return $uID;
	}

	/**
	 *
	 * @todo fix this, exception
	 * @return void
	 */
	public function unlock()
	{
		$wallData = mp_get_session( $_POST['sid'] );
		$api = new BillingFoxAPI;

		if ( $wallData ) $api->validate( $wallData );
		// false
	}

	public function unlockContent( $shortcodeIDs = null )
	{
		$shortcodeIDs = $shortcodeIDs ? $shortcodeIDs : $_POST['shortcodeIDs'];

		if ( isset( $shortcodeIDs ) ) :
			$shortcodeIDs = explode( ',', $shortcodeIDs );

			foreach ( $shortcodeIDs as $shortcodeID ) :
				$unlocked = mp_get_session( $shortcodeID );

				if ( $unlocked ) {
					$unlocked->status = 'unlocked';
					mp_set_session( $shortcodeID, $unlocked );
				}
			endforeach;
		else:
			$this->httpCode = 400;
			$this->setResponse( 'Could not unlock content!' );
		endif;

		$this->response();
	}

	public static function processUnlockResponse( array $spends )
	{
		$return['shortcodeContent'] = [];
		$return['spends'] = [];

		foreach ( $spends as $spend ) :
			if ( mp_get_session( $spend['description'] )->status === 'locked' ) :
				$return['shortcodeContent'][ $spend['description'] ] = mp_get_session( $spend['description'] )->content;
				$return['spends'][] = $spend['description'];
			endif;
		endforeach;

		return $return;
	}
}