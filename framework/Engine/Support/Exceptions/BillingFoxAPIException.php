<?php
namespace MPEngine\Support\Exceptions;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

class BillingFoxAPIException extends \Exception
{
	public function msg()
	{
		return '<strong>Error:</strong> ' . $this->getMessage();
	}
}