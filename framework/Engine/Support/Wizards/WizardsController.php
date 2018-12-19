<?php
namespace MPEngine\Support\Wizards;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\Support\Blueprints\WizardsInterface;
use MPEngine\Support\Blueprints\HookableInterface;

class WizardsController implements HookableInterface
{
	private static $activeWizard;

	protected $wizards = [
		SetupWizardController::class,
	];

	public function initWizards()
	{
		foreach ( $this->wizards as $wizard ) :
			if ( class_exists( $wizard ) && is_subclass_of( $wizard, WizardsInterface::class ) ) $wizard::register();
		endforeach;
	}

	public function hook()
	{
		$this->initWizards();
	}
}