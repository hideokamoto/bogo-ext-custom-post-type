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

    /**
     * Test sanitize_post_types method with valid input
     */
    public function test_sanitize_post_types_with_valid_input() {
        $instance = new Bogo_Custom_Post_Types_Support();

        // カスタム投稿タイプを登録
        register_post_type('book', [
            'public' => true,
            'label' => 'Books'
        ]);
        register_post_type('movie', [
            'public' => true,
            'label' => 'Movies'
        ]);

        $input = ['book', 'movie'];
        $result = $instance->sanitize_post_types($input);

        $this->assertEqualsCanonicalizing(['book', 'movie'], $result);
    }

    /**
     * Test sanitize_post_types method with invalid input
     */
    public function test_sanitize_post_types_with_invalid_input() {
        $instance = new Bogo_Custom_Post_Types_Support();

        // 文字列を渡した場合
        $result = $instance->sanitize_post_types('not_an_array');
        $this->assertEquals([], $result);

        // nullを渡した場合
        $result = $instance->sanitize_post_types(null);
        $this->assertEquals([], $result);
    }

    /**
     * Test sanitize_post_types filters out invalid post types
     */
    public function test_sanitize_post_types_filters_invalid_types() {
        $instance = new Bogo_Custom_Post_Types_Support();

        register_post_type('book', [
            'public' => true,
            'label' => 'Books'
        ]);

        $input = ['book', 'invalid_type', 'another_invalid'];
        $result = $instance->sanitize_post_types($input);

        $this->assertEqualsCanonicalizing(['book'], $result);
    }

    /**
     * Test add_custom_post_types method
     */
    public function test_add_custom_post_types() {
        $instance = new Bogo_Custom_Post_Types_Support();

        // オプションを設定
        update_option('bogo_cpt_support_post_types', ['book', 'movie']);

        // メソッドをテスト
        $result = $instance->add_custom_post_types(['post', 'page']);

        $this->assertEqualsCanonicalizing(['post', 'page', 'book', 'movie'], $result);

        // Clean up
        delete_option('bogo_cpt_support_post_types');
    }

    /**
     * Test add_custom_post_types removes duplicates
     */
    public function test_add_custom_post_types_removes_duplicates() {
        $instance = new Bogo_Custom_Post_Types_Support();

        // オプションを設定（重複を含む）
        update_option('bogo_cpt_support_post_types', ['book', 'post']);

        // メソッドをテスト（既存に 'post' が含まれている）
        $result = $instance->add_custom_post_types(['post', 'page']);

        $this->assertEqualsCanonicalizing(['post', 'page', 'book'], $result);

        // Clean up
        delete_option('bogo_cpt_support_post_types');
    }

    /**
     * Test add_custom_post_types with invalid localizable parameter
     */
    public function test_add_custom_post_types_with_invalid_localizable() {
        $instance = new Bogo_Custom_Post_Types_Support();

        update_option('bogo_cpt_support_post_types', ['book']);

        // 文字列を渡した場合
        $result = $instance->add_custom_post_types('not_an_array');
        $this->assertEqualsCanonicalizing(['book'], $result);

        // Clean up
        delete_option('bogo_cpt_support_post_types');
    }

    /**
     * Test add_custom_post_types with empty option
     */
    public function test_add_custom_post_types_with_empty_option() {
        $instance = new Bogo_Custom_Post_Types_Support();

        delete_option('bogo_cpt_support_post_types');

        $result = $instance->add_custom_post_types(['post', 'page']);

        $this->assertIsArray($result);
        $this->assertEquals(['post', 'page'], $result);
    }
}
