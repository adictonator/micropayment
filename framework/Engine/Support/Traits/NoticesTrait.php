<?php
namespace MPEngine\Support\Traits;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

trait NoticesTrait
{
	private $msg;

	private $class;

	private $dismissible = ' is-dismissible';

	public function setNotice( string $type, string $msg )
	{
		$this->class = 'notice-' . $type;
		$this->msg = $msg;
		$this->dismissible = $type === 'error' ? false : $this->dismissible;

		/** Prevent showing notices multiple times. */
		if ( ! get_transient( 'mp-malformed-settings' ) ) :
			add_action( 'admin_notices', [$this, 'getNotice'] );
			delete_transient( 'mp-malformed-settings' );
		endif;
	}

	public function getNotice()
	{
		/** Prevent showing blank notices. */
		if ( $this->msg )
			echo "<div class='notice {$this->class}{$this->dismissible}'><p>{$this->msg}</p></div>";
	}
}