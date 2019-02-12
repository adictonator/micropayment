<?php
namespace MicroPay\Controllers\Dash\Menus;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\Support\Traits\ViewsTrait;
use MicroPay\Controllers\BaseController;
use MPEngine\Support\Traits\SettingsTrait;

/**
 * Base functions for WP Admin Dashboard Menu.
 *
 * @author Adictonator <adityabhaskarsharma@gmail.com>
 * @package MicroPayment
 * @since 1.0.0
 * @abstract
 */
abstract class BaseMenuController
{
	use ViewsTrait, SettingsTrait;

	/**
	 * Access level to the plugin.
	 *
	 * @var string
	 */
	protected $accessLevel = 'administrator';

	/**
	 * Model for the current class.
	 *
	 * @var object BaseModel
	 */
	public $model;

	/**
	 * Slug for the menu/submenu.
	 *
	 * @var string
	 */
	public $slug = MP_PLUGIN_SLUG;

	/**
	 * Type of menu. Can be "main" or "sub".
	 *
	 * @var string
	 */
	protected $menuType;

	/**
	 * Tells weather to use main menu's slug as submenu's slug.
	 *
	 * @var boolean
	 */
	protected $useMainMenu;

	/**
	 *
	 * @param string $menuType
	 * @param boolean $useMainMenu
	 */
	public function __construct( string $menuType = 'sub', bool $useMainMenu = false )
	{
		$this->menuType = $menuType;
		$this->useMainMenu = $useMainMenu;
		$this->slug = $this->menuType == 'main' ? $this->slug : (
			$this->useMainMenu ? $this->slug : mp_menu_slug( static::TITLE )
		);

		method_exists( $this, 'model' ) ? $this->model() : false;
	}

	/**
	 * Generates the view for WP menu/submenu.
	 *
	 * @return void
	 */
	abstract protected function view();

	// /**
	//  * Loads assets for the current WP menu/submenu.
	//  *
	//  * @param object $menuInstance
	//  * @return void
	//  */
    // abstract protected function assets();

	/**
	 * Initializes WP menu page.
	 *
	 * @return void
	 */
	protected function mainMenu()
	{
		add_menu_page(
			static::TITLE,
			MP_PLUGIN_LONG_NAME,
			$this->accessLevel,
			$this->slug,
			[$this, 'menuFunction'],
			'none'
		);
	}

	/**
	 * Initializes WP Submenu page.
	 *
	 * @return void
	 */
	protected function subMenu()
	{
		add_submenu_page(
			MP_PLUGIN_SLUG,
			mp_menu_title( static::TITLE ),
			static::TITLE,
			$this->accessLevel,
			$this->slug,
			[$this, 'menuFunction']
		);
	}

	/**
	 * Set the model for the current class.
	 *
	 * @param BaseModel $model
	 * @return void
	 */
	protected function setModel( BaseModel $model )
	{
		$this->model = $model;
	}

	/**
	 * Prepare admin menu.
	 *
	 * @return void
	 */
	public function load()
    {
		if ( $this->menuType === 'main' ) :
			$this->mainMenu();
		else:
			$this->subMenu();
		endif;
	}

	/**
	 * Triggers function to generate menu view.
	 *
	 * @return void
	 */
    public function menuFunction()
    {
		$this->view();
    }
}