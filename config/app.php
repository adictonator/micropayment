<?php
defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\Support\Blueprints\HookableInterface;

class ServiceProvider
{
	protected static $providers;

	public static function load()
	{
		self::$providers = [
			MPEngine\Wizards\WizardsController::class,
			MPEngine\Core\CoreDependenciesController::class,
			MPEngine\WooCommerce\PaymentGatewaysController::class,
			MicroPay\Controllers\Dash\DashMenusController::class,
			MicroPay\Controllers\Shortcodes\ShortcodesController::class,
		];

		return new self;
	}

	public static function register()
	{
		foreach ( self::$providers as $provider ) :
			if ( is_subclass_of( $provider, HookableInterface::class ) ) ( new $provider )->hook();
		endforeach;
	}

	public static function boot()
	{
		self::load()->register();
	}
}