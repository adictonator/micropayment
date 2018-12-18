<?php
namespace MPEngine\Support\Wizards;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\Support\Traits\ViewsTrait;
use MPEngine\Support\Blueprints\WizardsInterface;

class SetupWizardController implements WizardsInterface
{
	use ViewsTrait;

	public function view()
	{
		$this->setView( 'installer.setupWiz' );
		exit;
	}

	public static function loadView()
	{
		return ( new self )->view();
	}
}