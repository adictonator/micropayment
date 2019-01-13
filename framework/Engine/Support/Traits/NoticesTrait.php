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

		add_action( 'admin_notices', [$this, 'getNotice'] );
	}

	public function getNotice()
	{
		echo "<div class='notice {$this->class}{$this->dismissible}'><p>{$this->msg}</p></div>";
	}
}