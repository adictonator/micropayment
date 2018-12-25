<?php
namespace MPEngine\Core;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\Support\Blueprints\HookableInterface;

class CoreDependenciesController implements HookableInterface
{
	private $js = [
		'Server.mp-server',
	];

	public function loadCoreAssets()
	{
		// foreach ( $this->js as $key => $js ) :
		// 	// $l = mp_path_resolver( $js, 'asset', 'js' );

		// 	// echo "<pre>";
		// 	// print_r($l);
		// 	// echo "</pre>";
		// endforeach;
		wp_enqueue_script( 'mp-js', MP_FW_ASSETS_URL .'js/Server/mp-server.js', ['wp-util'], MP_VER, true );
		wp_enqueue_script( 'mp-js-app', MP_FW_ASSETS_URL .'js/app.js', [], MP_VER, true );
		wp_enqueue_style( 'mp-css', MP_FW_ASSETS_URL .'css/app.css');
	}

	public function hook()
	{
		add_action( 'wp_enqueue_scripts', [$this, 'loadCoreAssets'] );
		add_action( 'admin_enqueue_scripts', [$this, 'loadCoreAssets'] );
	}
}
