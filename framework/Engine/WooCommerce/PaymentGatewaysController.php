<?php
namespace MPEngine\WooCommerce;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\Support\Blueprints\HookableInterface;

class PaymentGatewaysController implements HookableInterface
{
	protected $gateways = [
		MPPaymentGateway::class,
	];

	public function initGateways( $gateways )
	{
		foreach ( $this->gateways as $gateway ) :
			if ( class_exists( $gateway ) && is_subclass_of( $gateway, \WC_Payment_Gateway::class ) ) $gateways[] = $gateway;
		endforeach;

		return $gateways;
	}

	public function hook()
	{
		add_filter( 'woocommerce_payment_gateways', [$this, 'initGateways'] );
	}
}