<?php
namespace MPEngine\WooCommerce;

use MPEngine\BillingFox\BillingFoxAPI;
use MPEngine\BillingFox\BillingFoxUserController;

class MPPaymentGateway extends \WC_Payment_Gateway
{
	public function __construct()
	{
		$this->id = MP_PLUGIN_SLUG . '_gateway';
		$this->api = new BillingFoxAPI;
        $this->icon = MP_FW_ASSETS_URL . '/images/logo.png';
        $this->has_fields = false;
        $this->method_title = __( 'BillingFox' );
        $this->method_description = __( 'Metered billing for busy developers. Requires users to be logged in.' );
        $this->supports = [];

        $this->init_settings();

        $this->title        = $this->get_option( 'title', 'BillingFox' );
        $this->description  = $this->get_option( 'description' );
        $this->custom_exchange_currencies = $this->get_option( 'custom_exchange_currencies', ['EUR', 'USD'] );

        $this->init_form_fields();

        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, [$this, 'process_admin_options'] );
	}

    public function init_form_fields()
    {
		$availableCurrencies = get_woocommerce_currencies();

        $fields = [
            'enabled' => [
                'title'   => __( 'Enable/Disable' ),
                'type'    => 'checkbox',
                'label'   => __( 'Enable BillingFox Payment' ),
                'default' => 'yes'
            ],
            'title' => [
                'title'       => __( 'Title' ),
                'type'        => 'text',
                'description' => __( 'This controls the title for the payment method the customer sees during checkout.' ),
                'default'     => __( 'BillingFox' ),
                'desc_tip'    => true,
            ],
            'description' => [
                'title'       => __( 'Description' ),
                'type'        => 'textarea',
                'description' => __( 'Payment method description that the customer will see on your checkout.' ),
                'default'     => __( 'Credits will be taken from your Account.' ),
                'desc_tip'    => true,
            ],
            'custom_exchange_currencies' => [
                'title'       => __( 'Currencies with custom coin value (default is '. MP_BF_PRICE .' per Credit)' ),
                'type'        => 'multiselect',
				'description' => __( 'Currency codes that deviate from 1 Credit = '. MP_BF_PRICE .'.' ),
				'class'		  => 'mp-has-select2',
                'options'     => $availableCurrencies,
                'desc_tip'    => true,
            ],
		];

		$currencies = is_array( $this->custom_exchange_currencies ) ? $this->custom_exchange_currencies : [];

		foreach ( $currencies as $currency ) :
            if ( empty( $availableCurrencies[ $currency ] ) ) continue;

            $fields[ 'custom_exchange_rate_' . strtolower( $currency ) ] = [
                'title'       => __( '1 Credit in ' . $availableCurrencies[ $currency ] ) . ' (' . $currency . ') ',
                'type'        => 'number',
                'description' => __( 'Currency codes that deviate from 1 Credit = '. MP_BF_PRICE .'.' ),
                'default'     => MP_BF_PRICE,
                'desc_tip'    => true,
                'custom_attributes' => [
                    'step' => MP_BF_PRICE,
                ],
            ];
        endforeach;

        $this->form_fields = apply_filters( 'billingfox_form_fields', $fields );
    }

	public function process_payment( $orderID )
	{
        $order = wc_get_order( $orderID );

		/** @var object WC_Order_Item_Product $item */
        foreach ( $order->get_items() as $item ) :
            if ( $item->get_product()->is_type( 'billingfox' ) ) :
                throw new RuntimeException(__('Cannot buy Credits with Credits'));
			endif;
        endforeach;

        $description = 'Order: #'. $order->get_order_number();
        $exchangeRate = $this->getExchangeRateFor( $order->get_currency( 'raw' ) );
		$coins = ceil( $order->get_total( 'raw' ) / $exchangeRate );
        $userID = BillingFoxUserController::getUserBfID( $order->get_user()->ID );

        if ( false === $userID ) {
            error_log('could not generate user id for billingfox');

            throw new RuntimeException('API Error');
        }

        try {
			$result = $this->api->spend( $userID, $coins, $description );

			/** Removing cached data to fetch latest one. */
			mp_remove_session( 'spends' );
			mp_remove_session( 'bfUser' );
        } catch (RuntimeException $e) {
            // payment failed... sry :(
            error_log('api error: '.$e->getMessage());

            throw new RuntimeException('API Error [billingfox:'.$e->getCode().']');
        }

        $order->update_status( 'completed', $result['message'] );

        wc_reduce_stock_levels( $orderID );
        WC()->cart->empty_cart();

        return [
            'result' => 'success',
            'redirect' => $this->get_return_url( $order ),
        ];
    }

	/**
	 * Return custom exchange currency value.
	 *
	 * @param float $currency
	 * @return float
	 */
    protected function getExchangeRateFor( $currency )
    {
        $exchange = (float) $this->get_option( 'custom_exchange_rate_'.strtolower( $currency ) );

        if ( $exchange <= 0 ) return MP_BF_PRICE;

        return $exchange;
    }
}