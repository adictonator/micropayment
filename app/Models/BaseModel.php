<?php
namespace MicroPay\Models;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

abstract class BaseModel
{
	protected $model;

	public function __construct( BaseModel $model )
	{
		$this->model = $model;
	}
}
