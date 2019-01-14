<?php

/**
 * Can't use namespace because WooCommerce lol.
 * Returns custom product type.
 *
 * @ignore Coding standards.
 * @author Adictonator <adityabhaskarsharma@gmail.com>
 * @package MicroPayment
 * @since 1.0.0
 */
class WC_Product_BillingFox extends \WC_Product
{
	/**
	 * Returns our custom product type.
	 *
	 * @return string
	 */
	public function get_type()
	{
		return 'billingfox';
	}
}