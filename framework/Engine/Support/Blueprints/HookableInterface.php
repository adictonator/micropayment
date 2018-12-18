<?php
namespace MPEngine\Support\Blueprints;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

interface HookableInterface
{
	public function hook();
}
