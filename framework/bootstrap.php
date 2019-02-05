<?php
/** Get PHP Autoloader. */
require_once dirname( MP_ROOT ) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
/** Get all the Service Providers. */
require_once dirname( MP_ROOT ) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'app.php';

/** Showtime. */
MPEngine\Ignition::ignite();
