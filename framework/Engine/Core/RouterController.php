<?php
namespace MPEngine\Core;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\Support\Blueprints\HookableInterface;

class RouterController implements HookableInterface
{
	public function listenAJAX()
	{
		if ( $this->validate( $_POST ) ) $this->resolveFormAction( $_POST );
		wp_die();
	}

	private function validate( $formData )
	{
		if ( isset( $formData[ MP_FORM_NONCE ] )
			&& wp_verify_nonce( $formData[ MP_FORM_NONCE ], $formData['mpAction'] ) ) return true;
		return false;
	}

	private function resolveFormAction( $actionData )
	{
		$controller = str_replace( ':', '\\', $actionData['mpController'] );
		$method = $actionData['mpAction'];

		if ( class_exists( $controller ) ) :
			$controllerClass = new $controller;
			if ( method_exists( $controllerClass, $method ) ) $controllerClass->$method();
		endif;
	}

	public function hook()
	{
		add_action( 'wp_ajax_listenAJAX', [$this, 'listenAJAX'] );
		add_action( 'wp_ajax_nopriv_listenAJAX', [$this, 'listenAJAX'] );
	}
}
