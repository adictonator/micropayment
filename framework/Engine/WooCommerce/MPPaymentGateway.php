<?php
namespace MPEngine\WooCommerce;

class MPPaymentGateway extends \WC_Payment_Gateway
{
	public function __construct()
	{
		$this->id                 = MP_PLUGIN_SLUG . '_gateway';
        $this->icon               = plugins_url('resources/img/billingfox.png', BILLING_FOX_PLUGIN_FILE);
        $this->has_fields         = false;
        $this->method_title       = __( 'ladvda', BILLING_FOX_TRANSLATE );
        $this->method_description = __( 'Metered billing for busy developers. Requires users to be logged in.', BILLING_FOX_TRANSLATE );

        $this->supports = [];

        $this->init_settings();

        // Define user set variables
        $this->title        = $this->get_option( 'title' );
        $this->description  = $this->get_option( 'description' );
        $this->custom_exchange_currencies = $this->get_option('custom_exchange_currencies', 'EUR,USD');

        $this->init_form_fields();

        // Actions
        add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, [$this, 'process_admin_options']);
	}

	public function getBalance()
    {
        $exchangeRate = $this->getExchangeRateFor(get_woocommerce_currency());
        $required = $this->normalizer->normalizeCoins(WC()->cart->get_total('raw') / $exchangeRate);
        $available = 0;
        $link = '';
        $target = '_blank';

        if (is_user_logged_in()) {
            $id = $this->normalizer->normalizeUser(wp_get_current_user());

            try {
                $data = $this->api->getIdentity($id);

                $available = (float)$data['balances']['current'];
                list($link, $target) = $this->getRechargeLink($data['link']);
            } catch (BillingFox_Api_Exception $e) {
                // failed to query -> show recharge then :)
            }
        }

        return [
            'available' => $available,
            'required' => $required,
            'recharge' => $link,
            'target' => $target,
        ];
    }

    public function init_form_fields()
    {
        $fields = [
            'enabled' => [
                'title'   => __( 'Enable/Disable', BILLING_FOX_TRANSLATE ),
                'type'    => 'checkbox',
                'label'   => __( 'Enable BillingFox Payment', BILLING_FOX_TRANSLATE ),
                'default' => 'yes'
            ],
            'title' => [
                'title'       => __( 'Title', BILLING_FOX_TRANSLATE ),
                'type'        => 'text',
                'description' => __( 'This controls the title for the payment method the customer sees during checkout.', BILLING_FOX_TRANSLATE ),
                'default'     => __( 'BillingFox', BILLING_FOX_TRANSLATE ),
                'desc_tip'    => true,
            ],
            'description' => [
                'title'       => __( 'Description', BILLING_FOX_TRANSLATE ),
                'type'        => 'textarea',
                'description' => __( 'Payment method description that the customer will see on your checkout.', BILLING_FOX_TRANSLATE ),
                'default'     => __( 'Credits will be taken from your Account.', BILLING_FOX_TRANSLATE ),
                'desc_tip'    => true,
            ],
            'custom_exchange_currencies' => [
                'title'       => __( 'Currencies with custom coin value (default is 0.01 per Credit)', BILLING_FOX_TRANSLATE ),
                'type'        => 'textarea',
                'description' => __( 'Currency codes that deviate from 1 Credit = 0.01.', BILLING_FOX_TRANSLATE ),
                'default'     => "USD,EUR",
                'desc_tip'    => true,
            ],
        ];

		$available_currencies = get_woocommerce_currencies();

        foreach (preg_split('![ \.,\n\r]!', $this->custom_exchange_currencies) as $currency) {
            if (empty($currency)) {
                continue;
            }

            $currency = trim($currency);

            if (empty($available_currencies[$currency])) {
                // ok, this currency does not exist for woocommerce - skip it!
                continue;
            }

            $fields['custom_exchange_rate_'.strtolower($currency)] = [
                'title'       => __( '1 Credit in '.$available_currencies[$currency], BILLING_FOX_TRANSLATE ).' ('.$currency.')',
                'type'        => 'number',
                'description' => __( 'Currency codes that deviate from 1 Credit = 0.01.', BILLING_FOX_TRANSLATE ),
                'default'     => "0.01",
                'desc_tip'    => true,
                'custom_attributes' => [
                    'step' => '0.01',
                ],
            ];
        }

        $this->form_fields = apply_filters( 'billingfox_form_fields', $fields );
    }

    public function process_payment( $order_id ) {

        $order = wc_get_order( $order_id );

        /** @var WC_Order_Item_Product $item */
        foreach($order->get_items() as $item) {
            if ($item->get_product()->is_type('billingfox')) {
                throw new RuntimeException(__('Cannot buy Credits with Credits'));
            }
        }

        $description = 'Order: '.$order->get_order_number();
        $exchangeRate = $this->getExchangeRateFor($order->get_currency('raw'));
        $coins = $this->normalizer->normalizeCoins($order->get_total('raw') / $exchangeRate);

        $user_id = $this->normalizer->normalizeUser($order->get_user());


        if (false === $user_id) {
            error_log('could not generate user id for billingfox');

            throw new RuntimeException('API Error');
        }

        try {
            $result = $this->api->spend($user_id, $coins, $description);
        } catch (BillingFox_Api_InsufficientCoins $e) {
            // present link for payment!
            error_log('insufficient funds of user');

            throw new RuntimeException(__('Insufficient funds on Micropayment.io'), 0, $e);
        } catch (BillingFox_Api_Exception $e) {
            // payment failed... sry :(
            error_log('api error: '.$e->getMessage());

            throw new RuntimeException('API Error [billingfox:'.$e->getCode().']');
        }


        // Mark as on-hold (we're awaiting the payment)
        $order->update_status( 'completed', $result['message'] );

        wc_reduce_stock_levels( $order_id );
        WC()->cart->empty_cart();

        return [
            'result'    => 'success',
            'redirect'  => $this->get_return_url( $order )
        ];
    }

    protected function getRechargeLink($link)
    {
        $slug = get_option(BillingFox_Admin_Setting::SETTING_WOOCOMMERCE_CATEGORY_SLUG, false);

        $plugin = BillingFox_Plugin::getInstance();

        /** @var BillingFox_WooCommerce_Loader $wc_loader */
        $wc_loader = $plugin->get(BillingFox_WooCommerce_Loader::class);

        $target = '_blank';

        if ($wc_loader->exists() && $slug && ($link = $wc_loader->getCategoryLink($slug))) {
            $target = 'self';
        }

        return [
            $link,
            $target,
        ];
    }

    /**
     * @param string $currency
     *
     * @return float
     */
    protected function getExchangeRateFor($currency)
    {
        $exchange = (float)$this->get_option('custom_exchange_rate_'.strtolower($currency));

        if ($exchange <= 0) {
            return 0.01;
        }

        return $exchange;
    }
}