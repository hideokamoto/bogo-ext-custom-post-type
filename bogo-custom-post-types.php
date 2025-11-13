<?php
/**
 * Plugin Name: Bogo Custom Post Types Support
 * Plugin URI: https://hidetaka.dev
 * Description: Bogoで使用するカスタム投稿タイプを選択できるプラグイン
 * Version: 1.0.0
 * Author: Hidetaka Okamoto
 * Author URI: https://hidetaka.dev
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: bogo-ext-custom-post-type
 * Requires at least: 5.0
 * Requires PHP: 7.4
 */

if (!defined('ABSPATH')) {
    exit;
}

class Bogo_Custom_Post_Types_Support {

    private $option_name = 'bogo_cpt_support_post_types';

    public function __construct() {
        add_filter('bogo_localizable_post_types', [$this, 'add_custom_post_types'], 10, 1);
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    /**
     * 管理メニューに設定ページを追加
     */
    public function add_admin_menu() {
        add_options_page(
            'Bogo Custom Post Types',
            'Bogo Custom Post Types',
            'manage_options',
            'bogo-cpt-support',
            [$this, 'settings_page']
        );
    }

    /**
     * 設定を登録
     */
    public function register_settings() {
        register_setting('bogo_cpt_support_group', $this->option_name);
    }

    /**
     * 設定画面のHTML
     */
    public function settings_page() {
        $post_types = get_post_types(['public' => true, '_builtin' => false], 'objects');
        $selected_types = get_option($this->option_name, []);

        ?>
        <div class="wrap">
            <h1>Bogo Custom Post Types Support</h1>
            <p>Bogoで多言語化するカスタム投稿タイプを選択してください。</p>

            <?php if (empty($post_types)): ?>
                <div class="notice notice-warning inline">
                    <p>カスタム投稿タイプが登録されていません。</p>
                </div>
            <?php else: ?>
                <form method="post" action="options.php">
                    <?php settings_fields('bogo_cpt_support_group'); ?>
                    <table class="form-table" role="presentation">
                        <tr>
                            <th scope="row">有効にするカスタム投稿タイプ</th>
                            <td>
                                <fieldset>
                                    <legend class="screen-reader-text"><span>有効にするカスタム投稿タイプ</span></legend>
                                    <?php foreach ($post_types as $post_type): ?>
                                        <p>
                                            <label>
                                                <input
                                                    type="checkbox"
                                                    name="<?php echo esc_attr($this->option_name); ?>[]"
                                                    value="<?php echo esc_attr($post_type->name); ?>"
                                                    <?php checked(in_array($post_type->name, (array)$selected_types)); ?>
                                                >
                                                <strong><?php echo esc_html($post_type->label); ?></strong>
                                                <code><?php echo esc_html($post_type->name); ?></code>
                                            </label>
                                        </p>
                                    <?php endforeach; ?>
                                </fieldset>
                            </td>
                        </tr>
                    </table>
                    <?php submit_button('設定を保存'); ?>
                </form>
            <?php endif; ?>

            <hr class="wp-header-end">
            <p class="description">注意: 設定変更後は「設定」→「パーマリンク」→「変更を保存」を実行してください。</p>
        </div>
        <?php
    }

    /**
     * カスタム投稿タイプをBogoの多言語対応に追加
     */
    public function add_custom_post_types($localizable) {
        $selected_types = get_option($this->option_name, []);

        if (!empty($selected_types) && is_array($selected_types)) {
            return array_merge($localizable, $selected_types);
        }

        return $localizable;
    }
}

/**
 * プラグイン初期化
 */
function bogo_cpt_support_init() {
    if (function_exists('bogo_localizable_post_types')) {
        new Bogo_Custom_Post_Types_Support();
    } else {
        add_action('admin_notices', 'bogo_cpt_support_admin_notice');
    }
}
add_action('plugins_loaded', 'bogo_cpt_support_init');

/**
 * Bogoが無効の場合の警告表示
 */
function bogo_cpt_support_admin_notice() {
    ?>
    <div class="notice notice-error">
        <p><strong>Bogo Custom Post Types Support:</strong> このプラグインはBogoプラグインが必要です。</p>
    </div>
    <?php
}
