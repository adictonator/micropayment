<?php
namespace MPEngine\Core;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\Support\Blueprints\HookableInterface;

class CoreDependenciesController implements HookableInterface
{
	public function loadCoreAssets()
	{
		wp_enqueue_script( 'mp-js', MP_FW_ASSETS_URL .'js/Server/mp-server.js', [], MP_VER );
		wp_enqueue_script( 'mp-js-app', MP_FW_ASSETS_URL .'js/app.js', ['jquery'], MP_VER );
		wp_enqueue_style( 'mp-css', MP_FW_ASSETS_URL .'css/app.css');
		wp_localize_script( 'mp-js', 'mp_helpers', [
			'url' => admin_url( 'admin-ajax.php' ) . '?action=listenAJAX',
			'nonce_key' => MP_FORM_NONCE,
			'nonce' => wp_create_nonce( MP_FORM_NONCE ),
			'mp_fw_url' => MP_FW_URL,
			'mp_bf_price' => MP_BF_PRICE,
		 ] );
	}

	public function loadFrontAssets()
	{
		wp_enqueue_script( 'mp-js-stripe', 'https://js.stripe.com/v3/', null, MP_VER );
		wp_enqueue_script( 'mp-js-shortcode', MP_FW_ASSETS_URL .'js/Shortcode/mp-shortcode-api.js', [], MP_VER );
		wp_enqueue_script( 'mp-js-stripe-api', MP_FW_ASSETS_URL .'js/Stripe/mp-stripe-api.js', [], MP_VER );
	}

	public function initSession()
	{
		if ( ! session_id() ) session_start();
	}

	/**
	 * Removes all MicroPayment related sessions.
	 *
	 * @return void
	 */
	public function clearSessionTraces()
	{
		mp_remove_session();
	}

	public function hook()
	{
		add_action( 'init', [$this, 'initSession'] );
		add_action( 'wp_enqueue_scripts', [$this, 'loadCoreAssets'] );
		add_action( 'wp_enqueue_scripts', [$this, 'loadFrontAssets'] );
		add_action( 'admin_enqueue_scripts', [$this, 'loadCoreAssets'] );
		add_action( 'admin_init', [$this, 'loadCoreAssets'] );
		add_action( 'wp_logout', [$this, 'clearSessionTraces'] );
	}
}
