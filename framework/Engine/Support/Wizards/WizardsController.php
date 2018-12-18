<?php
namespace MPEngine\Support\Wizards;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\Support\Blueprints\HookableInterface;

class WizardsController implements HookableInterface
{
	protected $wizards = [
		'setup' => SetupWizardController::class,
	];

	public function initWizards()
	{
		switch ( $_GET['page'] ) :
			case MP_PLUGIN_SLUG . '-setup':
				$this->wizards['setup']::loadView();
				break;
		endswitch;
	}

	public function hook()
	{
		add_action( 'init', [$this, 'initWizards'] );
	}
}