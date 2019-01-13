<?php
namespace MPEngine\WooCommerce;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\Support\Blueprints\HookableInterface;

class WooProductType implements HookableInterface
{
	public function hook()
	{
		add_action( 'product_type_selector', [$this, 'billingFoxProductType'] );
        add_filter( 'woocommerce_product_data_tabs', [$this, 'billingFoxProductTypeTabs'] );
        add_action( 'woocommerce_product_data_panels', [$this, 'billingFoxProductTypeOptions'] );
		add_action( 'woocommerce_process_product_meta', [$this, 'billingFoxProductTypeUpdate'] );
		// add_action('woocommerce_init', [$this, 'requireFiles']);
	}

	public function billingFoxProductType( $types )
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
        if( ! empty( $_POST['billingfox'] ) ) update_post_meta( $productID, 'billingfox_product', esc_attr( $_POST['billingfox'] ) );
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
