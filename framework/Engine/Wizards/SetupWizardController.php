<?php
namespace MPEngine\Wizards;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\Support\Traits\ViewsTrait;
use MPEngine\Support\Traits\SettingsTrait;
use MPEngine\Support\Blueprints\WizardsInterface;

class SetupWizardController implements WizardsInterface
{
	use ViewsTrait, SettingsTrait;

	const WIZARD_SLUG = MP_PLUGIN_SLUG . '-setup';

	protected $assets = [
		'css' => ['app.css'],
		'js' => ['app.js'],
	];

	public function view()
	{
		$this->setView( 'installer.setupWiz' );
		exit;
	}

	public static function loadView()
	{
		return ( new self )->view();
	}

	public static function checkRedirect()
	{
		if ( ! get_transient( MP_PLUGIN_SLUG ) ) return;

		delete_transient( MP_PLUGIN_SLUG );

		if ( ( ! empty( $_GET['page'] ) && in_array( $_GET['page'], [ self::WIZARD_SLUG] ) ) || is_network_admin() || isset( $_GET['activate-multi'] ) ) return;

		wp_safe_redirect( admin_url( 'index.php?page=' . self::WIZARD_SLUG ) );
        exit;
	}

	public function register()
    {
		register_activation_hook( MP_ROOT, [$this, 'activated'] );
		add_action( 'admin_init', [__CLASS__, 'checkRedirect'] );

        if ( ! empty( $_GET['page'] ) && $_GET['page'] === self::WIZARD_SLUG ) {
			add_action( 'admin_menu', [__CLASS__, 'tempWizardMenu'] );
			add_action( 'admin_init', [__CLASS__, 'loadView'] );
        }
	}

	public static function tempWizardMenu()
    {
		add_dashboard_page( '', '', 'manage_options', self::WIZARD_SLUG, '' );
	}

	public function activated()
    {
		$generalSettings = $this->getSettings();

		if ( empty( $generalSettings ) ) $this->initSettings();
        set_transient( MP_PLUGIN_SLUG, 1, 30 );
	}

	public function setup()
	{
		$generalSettings = is_object( $this->getSettings() ) ? $this->getSettings() : $this->initSettings();

		! isset( $_POST['api']['debug'] ) ? $_POST['api']['debug'] = 'no' : '';
		! isset( $_POST['api']['testMode'] ) ? $_POST['api']['testMode'] = 'no' : '';

		foreach ( $_POST as $key => $vals )
			if ( is_array( $vals ) )
				foreach ( $vals as $dKey => $val ) $generalSettings->$key->$dKey = $val;

		$this->setSettings( $generalSettings );
	}
}