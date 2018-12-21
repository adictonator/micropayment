<?php
namespace MicroPay\Controllers\Shortcodes;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\Support\Traits\ViewsTrait;

abstract class BaseShortcodeController
{
	use ViewsTrait;

	private static $wall = false;

	protected static $content;

	protected static $contentPrice;

	private static $validAttributes = [
		'price',
	];

	abstract static function function( $attr, $content = '' );

	public static function load()
	{
		add_shortcode( static::$name, [static::class, 'function'] );
	}

	protected static function wall()
	{
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
		self::$content = $content;
		self::$contentPrice = $attrs['price'];

		return self::checkWallStatus();
	}

	private static function shortcodeContent()
	{
		return static::$content;
	}

	protected static function validateAttributes( array $attrs )
	{
		if ( empty( $attrs ) ) throw new \Exception('Price attribute is required!');

		foreach ( $attrs as $attr => $value ) :
			if ( ! in_array( $attr, self::$validAttributes ) ) :
				throw new \Exception('Price attribute is required!');
				break;
			endif;
		endforeach;

		shortcode_atts( ['price' => $attrs['price']], $attrs, static::$name );
	}
}