<?php
namespace MPEngine\Support\Traits;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

trait ResponseTrait
{
	private $response;

	private $die;

	public $httpCode = 200;

	public function setResponse( $message )
	{
		$this->response = $message;
	}

	public function response( $die = false )
	{
		$this->die = $die;
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

		echo mp_error_json( $response, $this->httpCode );
		$this->die ? wp_die() : '';
	}

	private function successResponse( $response )
	{
		echo mp_success_json( $response, $this->httpCode );
		$this->die ? wp_die() : '';
	}
}