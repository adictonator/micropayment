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
		 ] );
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
		mp_remove_session( MP_SESSION_KEY );
	}

	public function hook()
	{
		add_action( 'init', [$this, 'initSession'] );
		add_action( 'wp_enqueue_scripts', [$this, 'loadCoreAssets'] );
		add_action( 'admin_enqueue_scripts', [$this, 'loadCoreAssets'] );
		add_action( 'admin_init', [$this, 'loadCoreAssets'] );
		add_action( 'wp_logout', [$this, 'clearSessionTraces'] );
	}
}
