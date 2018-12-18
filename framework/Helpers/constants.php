<?php

/** General Constants. */
define( 'DS', DIRECTORY_SEPARATOR );
define( 'MP_VER', '1.0' );
define( 'MP_DB_VER', MP_VER );
define( 'MP_ROOT_DIR', plugin_dir_path( MP_ROOT ) );
define( 'MP_ROOT_URL', plugin_dir_url( MP_ROOT ) );
define( 'MP_VIEWS_DIR', MP_ROOT_DIR . 'resources' . DS . 'views' . DS );
define( 'MP_VIEWS_URL', MP_ROOT_URL . 'resources' . DS . 'views' . DS );

/** Files and extensions. */
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
