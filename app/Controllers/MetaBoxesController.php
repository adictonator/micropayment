<?php
namespace MicroPay\Controllers;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\Support\Blueprints\HookableInterface;
use MPEngine\Support\Traits\ViewsTrait;

class MetaBoxesController implements HookableInterface
{
	use ViewsTrait;

	public function meta() {
		add_meta_box( MP_PLUGIN_SLUG, MP_PLUGIN_SHORT_NAME . ' Paywall Config', [$this, 'renderContent'], 'post', 'side' );
	}

	public function renderContent()
	{
		return $this->setView( 'metabox.wall' );
	}

	public function hook()
	{
		add_action( 'add_meta_boxes', [$this, 'meta'] );
	}
}