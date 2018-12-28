<?php
namespace MPEngine\Wizards;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

use MPEngine\Support\Traits\ViewsTrait;
use MPEngine\Support\Blueprints\WizardsInterface;

class SetupWizardController implements WizardsInterface
{
	use ViewsTrait;

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

	public static function register()
    {
		register_activation_hook( MP_ROOT, [__CLASS__, 'activated'] );
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

	public static function activated()
    {
		$generalSettings = get_option( MP_GENERAL_SETTINGS_KEY, [] );

		if ( empty( $generalSettings ) ) self::initGeneralSettings();
        set_transient( MP_PLUGIN_SLUG, 1, 30 );
	}

	public function setup()
	{
		$data = mp_filter_form_data( $_POST );
		$generalSettings = get_option( MP_GENERAL_SETTINGS_KEY, [] );
		$key = $data['key'];

		foreach ( $data[ $key ] as $dKey => $val ) :
			$generalSettings[ $key ][ $dKey ]['value'] = $val;
		endforeach;

		update_option( MP_GENERAL_SETTINGS_KEY, $generalSettings );
	}

	private function initGeneralSettings()
	{
		$generalSettings = [
			'api' => [
				'mode' => [
					'label' => __( 'API Test Mode' ),
					'value' => 'yes',
				],
				'debug' => [
					'label' => __( 'Debugging Mode' ),
					'value' => 'no',
				],
			],
			'woo' => [

			],
			'general' => [

			],
		];

		update_option( MP_GENERAL_SETTINGS_KEY, $generalSettings );
	}
}