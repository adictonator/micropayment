<?php
defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\Support\Blueprints\HookableInterface;

/**
 * Service provider for the plugin.
 * Bootstraps all the required classes and hooks
 * them to WordPress.
 *
 * @author Adictonator <adityabhaskarsharma@gmail.com>
 * @package MicroPayment
 * @since 1.0.0
 */
class ServiceProvider
{
	/**
	 * Holds all the dependent classes to bootstrap.
	 *
	 * @var array
	 */
	protected static $providers;

	/**
	 * Bootstraps all the required classes.
	 *
	 * @return object Self instance.
	 */
	public static function load()
	{
		/** Loading providers as per their priority. */
		self::$providers = [
			MPEngine\Core\CoreDependenciesController::class,
			MPEngine\Core\RouterController::class,
			MPEngine\Wizards\WizardsController::class,
			MPEngine\WooCommerce\BillingFoxProductType::class,
			MPEngine\WooCommerce\PaymentGatewaysController::class,
			MicroPay\Controllers\Dash\DashMenusController::class,
			MicroPay\Controllers\MetaBoxesController::class,
			MicroPay\Controllers\Shortcodes\ShortcodesController::class,
		];

		return new self;
	}

	/**
	 * Hooks all the classes to WordPress.
	 *
	 * @return void
	 */
	public static function register()
	{
		foreach ( self::$providers as $provider ) :
			if ( is_subclass_of( $provider, HookableInterface::class ) ) ( new $provider )->hook();
		endforeach;
	}

	/**
	 * Boots all the hooked classes as plugin loads.
	 *
	 * @return void
	 */
	public static function boot()
	{
		self::load()->register();
	}
}