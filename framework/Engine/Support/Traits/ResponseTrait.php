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

	public function toObj( $jsonString )
	{
		return ( object ) json_decode( $jsonString, true );
	}

	private function toJSON()
	{
		return json_encode( $this->responseObj );
	}
}