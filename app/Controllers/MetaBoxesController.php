<?php
namespace MicroPay\Controllers;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\Support\Traits\ViewsTrait;
use MPEngine\Support\Blueprints\HookableInterface;

class MetaBoxesController implements HookableInterface
{
	use ViewsTrait;

	public $assets = [
		'js' => ['app.js'],
	];

	public function meta() {
		add_meta_box( MP_PLUGIN_SLUG, MP_PLUGIN_SHORT_NAME . ' Paywall Config', [$this, 'renderContent'], 'post', 'side' );
	}

	public function renderContent()
	{
		return $this->setView( 'metabox.wall' );
	}

	public function c( $postID )
	{
		if ( isset( $_POST['mp_sc_price'] ) ) :
			update_post_meta( $postID, '__mp_paywall_price', $_POST['mp_sc_price'] );
		endif;
	}

	public function hook()
	{
		add_action( 'add_meta_boxes', [$this, 'meta'] );
		add_action( 'save_post', [$this, 'c'] );
	}
}