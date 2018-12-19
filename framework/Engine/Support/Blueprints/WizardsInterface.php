<?php
namespace MPEngine\Support\Blueprints;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

interface WizardsInterface
{
	public function view();

	public static function loadView();

	public static function register();
}
