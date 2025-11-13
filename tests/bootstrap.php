<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Bogo_Custom_Post_Types_Support
 */

// Composer autoloader must be loaded before WP_PHPUNIT__DIR will be available
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Give access to tests_add_filter() function.
$_tests_dir = getenv('WP_TESTS_DIR');

if (!$_tests_dir) {
    // wp-env uses /wordpress-phpunit for test library
    $_tests_dir = '/wordpress-phpunit';
}

// Fallback to wordpress-develop location for wp-env
if (!file_exists("{$_tests_dir}/includes/functions.php") && file_exists('/wordpress-phpunit/includes/functions.php')) {
    $_tests_dir = '/wordpress-phpunit';
}

// Forward custom PHPUnit Polyfills configuration to PHPUnit bootstrap file.
$_phpunit_polyfills_path = getenv('WP_TESTS_PHPUNIT_POLYFILLS_PATH');
if (false !== $_phpunit_polyfills_path) {
    define('WP_TESTS_PHPUNIT_POLYFILLS_PATH', $_phpunit_polyfills_path);
}

if (!file_exists("{$_tests_dir}/includes/functions.php")) {
    echo "Could not find {$_tests_dir}/includes/functions.php" . PHP_EOL;
    echo "Make sure you're running tests inside wp-env with: npm run test:php" . PHP_EOL;
    exit(1);
}

// Give access to tests_add_filter() function.
require_once "{$_tests_dir}/includes/functions.php";

/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin() {
    require dirname(__DIR__) . '/bogo-custom-post-types.php';
}

tests_add_filter('muplugins_loaded', '_manually_load_plugin');

// Start up the WP testing environment.
require "{$_tests_dir}/includes/bootstrap.php";
