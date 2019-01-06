<?php

/** General Constants. */
define( 'DS', DIRECTORY_SEPARATOR );
define( 'MP_VER', '1.0' );
define( 'MP_DB_VER', MP_VER );

/** Files and directories. */
define( 'MP_ROOT_DIR', plugin_dir_path( MP_ROOT ) );
define( 'MP_ROOT_URL', plugin_dir_url( MP_ROOT ) );
define( 'MP_VIEWS_DIR', MP_ROOT_DIR . 'resources' . DS . 'views' . DS );
define( 'MP_VIEWS_URL', MP_ROOT_URL . 'resources' . '/' . 'views' . '/' );
define( 'MP_FW_DIR', MP_ROOT_DIR . 'framework' . DS );
define( 'MP_FW_URL', MP_ROOT_URL . 'framework' . '/' );
define( 'MP_FW_ASSETS_URL', MP_FW_URL . 'assets' . '/' );
define( 'MP_FW_ASSETS_DIR', MP_FW_DIR . 'assets' . DS );

/** Files extensions. */
define( 'MP_VIEWS_EXT', '.view.php' );
define( 'MP_INCLD_EXT', '.inc.php' );
define( 'MP_FILE_TYPES', [
	'view' => MP_VIEWS_EXT,
	'inc'  => MP_INCLD_EXT,
	'css'  => '.css',
	'js'   => '.js',
]);

/** Plugin constants. */
define( 'MP_PLUGIN_NAME', 'MicroPayment' );
define( 'MP_PLUGIN_SLUG', 'micropay' );
define( 'MP_PLUGIN_SHORT_NAME', 'MicroPay' );
define( 'MP_PLUGIN_LONG_NAME', 'MicroPayment IO' );
define( 'MP_PLUGIN_MENU_TITLE', MP_PLUGIN_LONG_NAME . ' &mdash; ' );
define( 'MP_GENERAL_SETTINGS_KEY', MP_PLUGIN_SLUG . '_settings' );

/** Form constants. */
define( 'MP_FORM_NONCE', MP_PLUGIN_SLUG . '_nonce' );

/** API constants. */
define( 'LIVE_EP', 'live.billingfox.com' );
define( 'TEST_EP', 'test.billingfox.com' );
define( 'BF_UID', 'billing_fox_user_id' );

