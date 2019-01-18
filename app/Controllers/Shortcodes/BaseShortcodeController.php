<?php
namespace MicroPay\Controllers\Shortcodes;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\BillingFox\BillingFoxAPI;
use MPEngine\Support\Traits\ViewsTrait;
use MPEngine\Support\Exceptions\ShortcodeException;

abstract class BaseShortcodeController
{
	use ViewsTrait;

	private $wall = true;

	protected $viewMessage;

	protected $shortcodeContents;

	const VIEW_ERROR_MESSAGE = 'Some attributes are missing!';

	const VIEW_WALL_MESSAGE = 'Pay Money to Unlock!';

	abstract function function( $attr, $content = '' );

	public function load()
	{
		add_shortcode( static::$name, [$this, 'function'] );
	}

	protected function validateAttributes( $content, $attrs )
	{
		$attrs = ! is_array( $attrs ) ? [] : $attrs;

		if ( ! empty( static::$args ) ) :
			foreach ( static::$args as $arg ) :
				if ( strpos( $arg, ':req' ) !== false && ( ! array_key_exists( str_replace( ':req', '', $arg ), $attrs ) || is_null( $attrs[ str_replace( ':req', '', $arg ) ] ) ) ) :
					return $this->incompleteShortcode();
				endif;
			endforeach;
		endif;

		return $this->processShortcodeContent( $content, $attrs );
	}

	private function wall()
	{
		$this->viewMessage = self::VIEW_WALL_MESSAGE;
		return $this->getWallContent();
	}

	public function hasWall()
	{
		if ( $this->shortcodeContents ) $this->wall = ( new BillingFoxAPI )->needWall( $this->shortcodeContents->attrs->uid );

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

	private function getErrorContent()
	{
		ob_start();
		$this->setView( 'shortcode.error' );
		$errorContent = ob_get_contents();
		ob_end_clean();

		return $errorContent;
	}

	private function processShortcodeContent( $content, $attrs )
	{
		$checkUnlocked = mp_get_session( $attrs['uid'] );

		mp_set_session( $attrs['uid'], $this->shortcodeContents = (object) [
			'content' => $content,
			'attrs'   => (object) $attrs,
			'status'  => $checkUnlocked ? $checkUnlocked->status : 'locked',
		]);

		return $this->checkWallStatus();
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

	private function incompleteShortcode()
	{
		$this->viewMessage = self::VIEW_ERROR_MESSAGE;

		return $this->getErrorContent();
	}
}