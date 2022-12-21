<?php

// define test environment
define( 'DKHIDDENTAGS_PHPUNIT', true );

// define fake ABSPATH
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', sys_get_temp_dir() );
}
// define fake DKHIDDENTAGS_ABSPATH
if ( ! defined( 'PLUGIN_ABSPATH' ) ) {
	define( 'PLUGIN_ABSPATH', sys_get_temp_dir() . '/wp-content/plugins/my-plugin/' );
}
if ( ! defined( 'PLUGIN_BASEPATH' ) ) {
	define( 'PLUGIN_ABSPATH', sys_get_temp_dir() . '/wp-content/plugins/my-plugin/' );
}

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../inc/libraries/autoloader.php';

// Include the class for PluginTestCase
require_once __DIR__ . '/inc/PluginTestCase.php';

// Since our plugin files are loaded with composer, we should be good to go
