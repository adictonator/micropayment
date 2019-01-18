<?php
namespace MPEngine\Core;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\Support\Traits\ResponseTrait;
use MPEngine\Support\Blueprints\HookableInterface;

class RouterController implements HookableInterface
{
	use ResponseTrait;

	public function listenAJAX()
	{
		if ( $this->validate( $_POST ) ) $this->resolveFormAction( $_POST );
		wp_die();
	}

	private function validate( $formData )
	{
		if ( isset( $formData[ MP_FORM_NONCE ] )
			&& wp_verify_nonce( $formData[ MP_FORM_NONCE ], MP_FORM_NONCE ) ) return true;

		if ( isset( $formData[ MP_FORM_NONCE ] )
			&& check_ajax_referer( $formData[ MP_FORM_NONCE ], MP_FORM_NONCE ) ) return true;

		$this->httpCode = 403;
		$this->response();
	}

	private function resolveFormAction( $actionData )
	{
		$controller = str_replace( ':', '\\', $actionData['mpController'] );
		$method = $actionData['mpAction'];
		$_POST = mp_filter_form_data( $actionData );

		if ( class_exists( $controller ) ) :
			$controllerClass = new $controller;
			$parentClass = get_parent_class( $controllerClass );
			if ( method_exists( $controllerClass, $method ) || method_exists( $parentClass, $method ) ) $controllerClass->$method();
		endif;
	}

	public function hook()
	{
		add_action( 'wp_ajax_listenAJAX', [$this, 'listenAJAX'] );
		add_action( 'wp_ajax_nopriv_listenAJAX', [$this, 'listenAJAX'] );
	}
}
