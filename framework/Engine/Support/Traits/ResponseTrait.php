<?php
namespace MPEngine\Support\Traits;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

trait ResponseTrait
{
	private $responseObj = [];

	public function setResponse( $type, $message )
	{
		$this->responseObj['type'] = $type;
		$this->responseObj['message'] = $message;
	}

	public function response()
	{
		return $this->toJSON();
	}

	private function toJSON()
	{
		echo json_encode( $this->responseObj );
	}
}