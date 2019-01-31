<?php
namespace MicroPay\Controllers\Shortcodes;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

class MicroPayShortcodeController extends BaseShortcodeController
{
	private $wall = true;

	protected $shortcodeContents;

	public static $name = 'micropay';

	public static $description = 'Restricts website content';

	public static $args = [
		'price:req',
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
		mp_set_session( 'toUnlock', $_POST['sid'] );

		$this->api->validate( $sid );
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

			$this->setResponse( [ 'type' => 'unlocking-done' ] );
		else:
			$this->httpCode = 400;
			$this->setResponse( 'Could not unlock content!' );
		endif;

		echo $this->response(1);
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

	protected function processShortcodeContent( $content, $attrs )
	{
		$checkUnlocked = mp_get_session( $attrs['uid'] );

		mp_set_session( $attrs['uid'], $this->shortcodeContents = (object) [
			'content' => $content,
			'attrs'   => (object) $attrs,
			'status'  => $checkUnlocked ? $checkUnlocked->status : 'locked',
		]);

		return $this->checkWallStatus();
	}

	private function wall()
	{
		$this->viewMessage = self::VIEW_WALL_MESSAGE;
		return $this->getWallContent();
	}

	public function hasWall()
	{
		if ( $this->shortcodeContents ) $this->wall = $this->api->needWall( $this->shortcodeContents->attrs->uid );

		return $this->wall;
	}

	private function getWallContent()
	{
		ob_start();
		$this->setView( 'shortcode.wall' );
		$wallContent = ob_get_contents();
		ob_end_clean();

		return $wallContent;
	}

	private function checkWallStatus()
	{
		if ( $this->hasWall() ) return $this->wall();
		else return $this->getShortcodeContent();
	}

	private function getShortcodeContent()
	{
		return $this->shortcodeContents->content;
	}
}