<?php
namespace MPEngine\Support\Exceptions;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

class BillingFoxAPIException extends \Exception
{
	public function msg()
	{
		return 'The view at <strong>' . $this->getMessage() . '</strong> doesn\'t exist!';
	}
}