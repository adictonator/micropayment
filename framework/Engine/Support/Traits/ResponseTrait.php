<?php
namespace MPEngine\Support\Traits;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

trait ResponseTrait
{
	private $response;

	public $httpCode = 200;

	public function setResponse( $message )
	{
		$this->response = $message;
	}

	public function response()
	{
		return $this->getResponse();
	}

	public function toObj( $jsonString )
	{
		return ( object ) json_decode( $jsonString, true );
	}

	private function getResponse()
	{
		if ( $this->httpCode >= 400 && $this->httpCode <= 451 ) return $this->errorResponse( $this->response );
		if ( $this->httpCode >= 200 && $this->httpCode <= 226 ) return $this->successResponse( $this->response );
	}

	private function errorResponse( $response )
	{
		$response = $response ? $response : get_status_header_desc( $this->httpCode );

		return wp_send_json_error( $response, $this->httpCode );
	}

	private function successResponse( $response )
	{
		return wp_send_json_success( $response, $this->httpCode );
	}
}