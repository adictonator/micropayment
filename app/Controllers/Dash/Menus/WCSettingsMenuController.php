<?php
namespace MicroPay\Controllers\Dash\Menus;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

class WCSettingsMenuController extends BaseMenuController
{
	const TITLE = 'WooCommerce Settings';

	public function view()
	{
		$settings = get_option( 'woocommerce_' . MP_PLUGIN_SLUG . '_gateway_settings', false );
		$products = $this->getBFProducts();
		$menuData = $this->getSettings();

		if ( $this->validateSettings( $menuData ) ) $this->setView( 'dash.wc.index', compact( 'settings', 'products' ) );
		else $this->setView( 'error.settings' );
	}

	public function update()
	{
		$settings = get_option( 'woocommerce_' . MP_PLUGIN_SLUG . '_gateway_settings', false );

		foreach ( $_POST as $key => $value ) :
			if ( isset( $settings[ $key ] ) ) $settings[ $key ] = stripcslashes( $value );
		endforeach;

		update_option( 'woocommerce_' . MP_PLUGIN_SLUG . '_gateway_settings', $settings );

		$return['type'] = 'success';
		$return['msg'] = 'Payment Gateway Settings Updated Successfully!';

		echo json_encode( $return );
	}

	private function getBFProducts()
	{
		$bfProducts = [];
		$products = new \WP_Query( [
			'post_type' => 'product',
			'tax_query' => [
				[
					'taxonomy' => 'product_type',
					'field'    => 'slug',
					'terms'    => 'billingfox',
				],
			],
		] );

		if ( $products->have_posts() ) :
			while ( $products->have_posts() ) : $products->the_post();

				$productMeta = wc_get_product( get_the_ID() );
				$bfProducts[ get_the_ID() ] = ( object ) [
					'id' => $productMeta->get_id(),
					'title' => $productMeta->get_name(),
					'description' => $productMeta->get_short_description(),
					'image' => $productMeta->get_image_id(),
					'link' => get_permalink( $productMeta->get_id() ),
					'meta' => ( object ) [
						'price' => $productMeta->get_price(),
						'created_at' => $productMeta->get_date_created(),
					],
				];

			endwhile;
			wp_reset_postdata();
		endif;

		return $bfProducts;
	}
}
