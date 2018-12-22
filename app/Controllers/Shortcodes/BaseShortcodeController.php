<?php
namespace MicroPay\Controllers\Shortcodes;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\Support\Traits\ViewsTrait;
use MPEngine\Support\Exceptions\ShortcodeException;

abstract class BaseShortcodeController
{
	use ViewsTrait;

	private $wall = true;

	protected $viewMessage;

	protected $shortcodeContents;

	private $requiredAttributes = [
		'price',
	];

	const VIEW_ERROR_MESSAGE = 'Some attributes are missing!';

	const VIEW_WALL_MESSAGE = 'Pay Money to Unlock!';

	abstract function function( $attr, $content = '' );

	public function load()
	{
		add_shortcode( $this->name, [$this, 'function'] );
	}

	protected function validateAttributes( $content, $attrs )
	{
		$attrs = ! is_array( $attrs ) ? [] : $attrs;

		foreach ( $this->requiredAttributes as $attr ) :
			if ( ! array_key_exists( $attr, $attrs ) ) :
				return $this->incompleteShortcode();
			endif;
		endforeach;

		return $this->processShortcodeContent( $content, $attrs );
	}

	private function wall()
	{
		$this->viewMessage = self::VIEW_WALL_MESSAGE;
		return $this->getWallContent();
	}

	private function hasWall()
	{
		//billingfox checks here

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
		$this->shortcodeContents = (object) [
			'content' => $content,
			'attrs' => (object) $attrs,
		];

		return $this->checkWallStatus();
	}

	private function checkWallStatus()
	{
		if ( $this->hasWall() ) return $this->wall();
		else return $this->shortcodeContent();
	}

	private function shortcodeContent()
	{
		return $this->shortcodeContents->content;
	}

	private function incompleteShortcode()
	{
		$this->viewMessage = self::VIEW_ERROR_MESSAGE;

		return $this->getErrorContent();
	}
}