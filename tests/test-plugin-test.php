<?php
/**
 * Class PluginTest
 *
 * @package Bogo_Custom_Post_Types_Support
 */

use Yoast\PHPUnitPolyfills\TestCases\TestCase;

/**
 * Sample test case.
 */
class PluginTest extends TestCase {

    /**
     * Test that the plugin class exists
     */
    public function test_plugin_class_exists() {
        $this->assertTrue(class_exists('Bogo_Custom_Post_Types_Support'));
    }

    /**
     * Test that the init function exists
     */
    public function test_init_function_exists() {
        $this->assertTrue(function_exists('bogo_cpt_support_init'));
    }

    /**
     * Test plugin initialization without Bogo
     */
    public function test_plugin_init_without_bogo() {
        // Bogoクラスが存在しない場合を確認
        $this->assertFalse(class_exists('Bogo'));
    }

    /**
     * Test option name constant
     */
    public function test_option_name() {
        $reflection = new ReflectionClass('Bogo_Custom_Post_Types_Support');
        $property = $reflection->getProperty('option_name');
        $property->setAccessible(true);

        $instance = new Bogo_Custom_Post_Types_Support();
        $option_name = $property->getValue($instance);

        $this->assertEquals('bogo_cpt_support_post_types', $option_name);
    }
}
