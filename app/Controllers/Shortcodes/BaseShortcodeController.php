<?php
namespace MicroPay\Controllers\Shortcodes;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\BillingFox\BillingFoxAPI;
use MPEngine\Support\Traits\ViewsTrait;
use MPEngine\Support\Traits\ResponseTrait;
use MPEngine\Support\Exceptions\ShortcodeException;

abstract class BaseShortcodeController
{
	use ViewsTrait, ResponseTrait;

	protected $api;

	protected $errorType;

	protected $viewMessage;

	const VIEW_ERROR_MESSAGE = 'Some attributes are missing!';

	const VIEW_WALL_MESSAGE = 'Pay Money to Unlock!';

	public function __construct()
	{
		$this->api = new BillingFoxAPI;
		$this->errorType = static::$name;
	}

	abstract function function( $attr, $content = '' );

	abstract protected function processShortcodeContent( $content, $attrs );

	public function load()
	{
		add_shortcode( static::$name, [$this, 'function'] );
	}

	protected function validateAttributes( $content, $attrs )
	{
		$attrs = ! is_array( $attrs ) ? [] : $attrs;

		if ( ! empty( static::$args ) ) :
			foreach ( static::$args as $arg ) :
				if ( strpos( $arg, ':req' ) !== false &&
					( ! array_key_exists( str_replace( ':req', '', $arg ), $attrs ) ||
					is_null( $attrs[ str_replace( ':req', '', $arg ) ] ) ) ) :
					return $this->incompleteShortcode();
				endif;
			endforeach;
		endif;

		return $this->processShortcodeContent( $content, $attrs );
	}

	protected function getErrorContent()
	{
		ob_start();
		$this->setView( 'shortcode.error' );
		$errorContent = ob_get_contents();
		ob_end_clean();

		return $errorContent;
	}

	private function incompleteShortcode()
	{
		$this->viewMessage = self::VIEW_ERROR_MESSAGE;

		return $this->getErrorContent();
	}
}