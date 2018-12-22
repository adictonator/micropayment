<?php
namespace MPEngine;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

final class Ignition
{
	private static $_instance;

	private static function __instance()
	{
		if ( null === self::$_instance ) :
			self::$_instance = new self;
		endif;

		return self::$_instance;
	}
	public static function ignite()
	{
		self::__instance();
		\ServiceProvider::boot();
	}
}
