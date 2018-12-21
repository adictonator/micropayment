<?php
namespace MicroPay\Controllers\Shortcodes;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\Support\Traits\ViewsTrait;
use MPEngine\Support\Exceptions\ShortcodeException;

abstract class BaseShortcodeController
{
	use ViewsTrait;

	private static $wall = true;

	protected static $viewMessage;

	protected static $shortcodeContents;

	private static $requiredAttributes = [
		'price',
	];

	const VIEW_ERROR_MESSAGE = 'Some attributes are missing!';

	const VIEW_WALL_MESSAGE = 'Pay Money to Unlock!';

	abstract static function function( $attr, $content = '' );

	public static function load()
	{
		add_shortcode( static::$name, [static::class, 'function'] );
	}

	protected static function wall()
	{
		self::$viewMessage = self::VIEW_WALL_MESSAGE;
		return self::getWallContent();
	}

	protected static function hasWall()
	{
		return self::$wall;
	}

	private static function getWallContent()
	{
		ob_start();
		static::loadView();
		$wallContent = ob_get_contents();
		ob_end_clean();

		return $wallContent;
	}

	protected static function checkWallStatus()
	{
		if ( self::hasWall() ) return self::wall();
		else return self::shortcodeContent();
	}

	protected static function processShortcodeContent( $content, $attrs )
	{
		self::$shortcodeContents = (object) [
			'content' => $content,
			'attrs' => (object) $attrs,
		];

		return self::checkWallStatus();
	}

	private static function shortcodeContent()
	{
		return static::$shortcodeContents->content;
	}

	protected static function validateAttributes( $content, $attrs )
	{
		$attrs = ! is_array( $attrs ) ? [] : $attrs;

		foreach ( self::$requiredAttributes as $attr ) :
			if ( ! array_key_exists( $attr, $attrs ) ) :
				return self::incompleteShortcode();
			endif;
		endforeach;

		return self::processShortcodeContent( $content, $attrs );
	}

	private static function incompleteShortcode()
	{
		self::$viewMessage = self::VIEW_ERROR_MESSAGE;

		return self::getWallContent();
	}
}