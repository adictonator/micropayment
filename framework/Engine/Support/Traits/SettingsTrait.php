<?php
namespace MPEngine\Support\Traits;

defined( 'ABSPATH' ) or die( 'Not allowed!' );

/**
 * Common functions to get/set general settings of the plugin.
 *
 * @author Aditya Bhaskar Sharma <adityabhaskarsharma@gmail.com>
 * @package MicroPayment
 * @since 1.0
 */
trait SettingsTrait
{
	use NoticesTrait;

	/**
	 * Returns setting data.
	 *
	 * @return mixed
	 */
	public function getSettings()
	{
		return get_option( MP_GENERAL_SETTINGS_KEY, false );
	}

	/**
	 * Saves setting data to the database.
	 *
	 * @param object $data
	 * @return void
	 */
	public function setSettings( $data )
	{
		return update_option( MP_GENERAL_SETTINGS_KEY, $data );
	}

	/**
	 * Basic data structure for Settings.
	 *
	 * @todo can be improved.
	 * @return object
	 */
	public function initSettings()
	{
		$settings = ( object ) [
			'api' => ( object ) [
				'mode' => ( object ) [
					'label' => __( 'API Test Mode' ),
					'value' => 'yes',
				],
				'debug' => ( object ) [
					'label' => __( 'Debugging Mode' ),
					'value' => 'no',
				],
				'key' => ( object ) [
					'label' => __( 'BillingFox API Key' ),
					'value' => null,
				]
			],
			'woo' => ( object ) [

			],
			'general' => ( object ) [

			],
		];

		$this->setSettings( $settings );

		return $settings;
	}

	/**
	 * Checks if the data stored is type object.
	 *
	 * @param mixed $data
	 * @return mixed
	 */
	public function validateSettings( $data )
	{
		/** Only checking for the type of data stored. */
		if ( ! is_object( $data ) ) $this->setNotice( 'error', 'Something is wrong!' );
		else return true;
	}
}