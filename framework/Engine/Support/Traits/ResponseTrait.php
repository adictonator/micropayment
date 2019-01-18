<?php
namespace MPEngine\Support\Traits;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

trait ResponseTrait
{
	private $responseObj = [];

	public $httpCode = 200;

	public function setResponse( $type, $message )
	{
		$this->responseObj['success'] = $type;
		$this->responseObj['data'] = $message;
	}

	public function response()
	{
		return $this->getResponse();
	}

	public function toObj( $jsonString )
	{
		return ( object ) json_decode( $jsonString, true );
	}

	private function toJSON()
	{
		return json_encode( $this->responseObj );
	}

	private function getResponse()
	{
		if ( $this->httpCode >= 400 && $this->httpCode <= 451 ) return $this->errorResponse( $this->responseObj );
		if ( $this->httpCode >= 200 && $this->httpCode <= 226 ) return $this->successResponse( $this->responseObj );
	}

	private function errorResponse( $response )
	{
		return wp_send_json_error( get_status_header_desc( $this->httpCode ), $this->httpCode );
	}

	private function successResponse( $response )
	{
		return $this->toJSON();
	}
}