<?php
namespace MPEngine\WooCommerce;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\Support\Blueprints\HookableInterface;

class BillingFoxProductType implements HookableInterface
{
	public function hook()
	{
		add_action( 'product_type_selector', [$this, 'setBillingFoxProductType'] );
		add_action( 'woocommerce_init', [$this, 'getBillingFoxProductType'] );
        add_filter( 'woocommerce_product_data_tabs', [$this, 'billingFoxProductTypeTabs'] );
        add_action( 'woocommerce_product_data_panels', [$this, 'billingFoxProductTypeOptions'] );
		add_action( 'woocommerce_process_product_meta', [$this, 'billingFoxProductTypeUpdate'], 30 );
		add_action( 'woocommerce_available_payment_gateways', [$this, 'billingFoxLimitGateway'] );
		add_action( 'woocommerce_billingfox_add_to_cart', [$this, 'addToCart'], 30 );
		add_action( 'woocommerce_before_single_product_summary', [$this, 'woocommerce_template_single_title'], 5 );
		remove_action( 'woocommerce_after_shop_loop_item_title', [$this, 'woocommerce_template_loop_price'], 5 );
		add_action( 'woocommerce_after_shop_loop_item_title', [$this, 'woocommerce_template_loop_price'], 10 );
		// add_action( 'woocommerce_single_product_summary', [$this, 'title'], 1 );
	}

	public function woocommerce_template_loop_price()
	{
		echo "<span>BillingFox Product</span>";
	}

	public function woocommerce_template_single_title($lol)
	{
	}

	/**
	 * "Add to Cart" form for custom product type.
	 *
	 * @return void
	 */
	public function addToCart()
	{
		wc_get_template( 'single-product/add-to-cart/simple.php' );
	}

	/**
	 * Removes custom gateway when custom product type is in cart.
	 *
	 * @todo needs some working.
	 * @param array $gateways
	 * @return array $gateways
	 */
	public function billingFoxLimitGateway( array $gateways )
	{
		if ( isset( $gateways[ MP_PLUGIN_SLUG . '_gateway' ] ) && $this->disableGateway() ) unset( $gateways[ MP_PLUGIN_SLUG . '_gateway' ] );

		return $gateways;
	}

	/**
	 * Checks if custom product is in the cart.
	 *
	 * @return boolean
	 */
	public function disableGateway()
	{
		if ( ! is_user_logged_in() ) return true;
		if ( ! $user = mp_get_session( 'bfUser' ) ) return true;
        if ( ! WC()->cart ) return false;

		$totalPrice = 0.00;

		foreach ( WC()->cart->get_cart() as $item ) :
			if ( $item['data'] instanceof \WC_Product_BillingFox ) return true;

			$totalPrice += $item['data']->get_price();
		endforeach;

		/** Check if the cart total is more than BF credits. */
		if ( $user['balances']['available'] < ( $totalPrice / MP_BF_PRICE ) ) return true;

        return false;
	}

	/**
	 * Requiring the file since namespace can't be used.
	 *
	 * @return object WC_Product_BillingFox
	 */
	public function getBillingFoxProductType()
	{
		require_once 'WC_Product_BillingFox.php';
	}

	public function setBillingFoxProductType( $types )
	{
		$types['billingfox'] = __( 'BillingFox' );

		return $types;
	}

    public function billingFoxProductTypeOptions()
    {
        echo '<div id="' . MP_PLUGIN_SLUG . '_product_data_tab" class="panel woocommerce_options_panel"><div class="options_group">';

        woocommerce_wp_text_input([
            'id'			=> 'billingfox',
            'label'			=> __( 'Amount of BillingFox' ),
            'desc_tip'		=> 'true',
            'description'	=> __( 'Be aware that you will get invoiced for the amount of Credits bought' ),
			'type' 			=> 'number',
            'custom_attributes' => [
				'autocomplete' => 'none',
				'min' => '0',
                'step' => '0.01',
            ],
        ]);

        echo '</div></div>';
    }

    public function billingFoxProductTypeUpdate( $productID )
    {
        if ( ! empty( $_POST['billingfox'] ) ) {
			update_post_meta( $productID, 'billingfox', esc_attr( $_POST['billingfox'] ) );
			update_post_meta( $productID, '_price', esc_attr( $_POST['billingfox'] ) );
			// update_post_meta( $productID, '_regular_price', esc_attr( $_POST['billingfox'] ) );
		}
    }

    public function billingFoxProductTypeTabs( $tabs )
    {
		$tabs['shipping']['class'][] = 'hide_if_billingfox';
		$tabs['advanced']['class'][] = 'hide_if_billingfox';
		$tabs['attribute']['class'][] = 'hide_if_billingfox';
		$tabs['variations']['class'][] = 'hide_if_billingfox';
		$tabs['linked_product']['class'][] = 'hide_if_billingfox';

		$tabs['billingfox'] = [
			'label' => __( 'BillingFox Data' ),
			'target' => MP_PLUGIN_SLUG . '_product_data_tab',
			'class' => ['show_if_billingfox'],
		];

        return $tabs;
	}
}
