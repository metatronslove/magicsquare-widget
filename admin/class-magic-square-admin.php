<?php
/**
 * Admin class for Magic Square Widget.
 *
 * @package MagicSquareWidget
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Magic_Square_Widget_Admin {

    private $plugin_name;
    private $version;

    public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function enqueue_styles( $hook ) {
        if ( strpos( $hook, 'magicsquare-widget' ) === false ) {
            return;
        }

        wp_enqueue_style(
            $this->plugin_name . '-admin',
            plugin_dir_url( __FILE__ ) . 'css/magic-square-admin.css',
            array(),
            $this->version,
            'all'
        );

        wp_enqueue_style( 'wp-codemirror' );
    }

    public function generate_widget_js() {
        header( 'Content-Type: application/javascript; charset=UTF-8' );
        $widget_options = get_option( 'magic_square_widget_settings', array() );
        $style_options  = get_option( 'magic_square_widget_style', array() );
        $code_options   = get_option( 'magic_square_widget_code', array() );
        include_once plugin_dir_path( dirname( __FILE__ ) ) . '/public/magicsquare-js.php';
        wp_die();
    }

    public function enqueue_scripts( $hook ) {
        if ( strpos( $hook, 'magicsquare-widget' ) === false ) {
            return;
        }

        wp_enqueue_script(
            $this->plugin_name . '-admin',
            plugin_dir_url( __FILE__ ) . 'js/magic-square-admin.js',
            array( 'jquery', 'wp-codemirror' ),
            $this->version,
            false
        );

        // Localize strings used in admin JavaScript.
        wp_localize_script(
            $this->plugin_name . '-admin',
            'magic_square_widget',
            array(
                'invalid_url' => esc_html__( 'Please enter a valid URL.', 'magicsquare-widget' ),
            )
        );
    }

    public function add_plugin_admin_menu() {
        add_menu_page(
            esc_html__( 'Magic Square Widget Dashboard', 'magicsquare-widget' ),
            esc_html__( 'Magic Square', 'magicsquare-widget' ),
            'manage_options',
            $this->plugin_name,
            array( $this, 'display_dashboard_page' ),
            'dashicons-grid-view',
            30
        );

        add_submenu_page(
            $this->plugin_name,
            esc_html__( 'Dashboard', 'magicsquare-widget' ),
            esc_html__( 'Dashboard', 'magicsquare-widget' ),
            'manage_options',
            $this->plugin_name,
            array( $this, 'display_dashboard_page' )
        );

        add_submenu_page(
            $this->plugin_name,
            esc_html__( 'Widget Settings', 'magicsquare-widget' ),
            esc_html__( 'Settings', 'magicsquare-widget' ),
            'manage_options',
            $this->plugin_name . '-settings',
            array( $this, 'display_settings_page' )
        );

        add_submenu_page(
            $this->plugin_name,
            esc_html__( 'Style Editor', 'magicsquare-widget' ),
            esc_html__( 'Style Editor', 'magicsquare-widget' ),
            'manage_options',
            $this->plugin_name . '-style-editor',
            array( $this, 'display_style_editor_page' )
        );

        add_submenu_page(
            $this->plugin_name,
            esc_html__( 'Code Editor', 'magicsquare-widget' ),
            esc_html__( 'Code Editor', 'magicsquare-widget' ),
            'manage_options',
            $this->plugin_name . '-code-editor',
            array( $this, 'display_code_editor_page' )
        );
    }

    public function display_dashboard_page() {
        // Artık iframe yok, direkt olarak external dashboard'a yönlendir
        $site_url = get_site_url();
        $version = MAGIC_SQUARE_WIDGET_VERSION;
        $magic_square_widget_dashboard_url = "https://one.fanclub.rocks/widgets/dashboard.php?site=" . urlencode($site_url) . "&widget=magicsquare-widget&version=" . urlencode($version);
        
        echo '<div class="wrap">';
        echo '<h1>' . esc_html__( 'Magic Square Widget Dashboard', 'magicsquare-widget' ) . '</h1>';
        echo '<div class="notice notice-info">';
        echo '<p>' . sprintf(
            // Translators: wellcome page of it opens a page that this widget could be downloaded
            esc_html__( 'The dashboard is hosted externally. %$1sClick here to open it in a new tab%$2s', 'magicsquare-widget' ),
            '<a href="' . esc_url($magic_square_widget_dashboard_url) . '" target="_blank">',
            '</a>'
        ) . '</p>';
        echo '</div>';
        
        // Local dashboard içeriğini göster
        include_once 'partials/dashboard-local.php';
        echo '</div>';
    }

    public function display_settings_page() {
        include_once 'partials/admin-settings.php';
    }

    public function display_style_editor_page() {
        include_once 'partials/admin-style-editor.php';
    }

    public function display_code_editor_page() {
        include_once 'partials/admin-code-editor.php';
    }

    public function register_settings() {
        register_setting(
            'magic_square_widget_options',
            'magic_square_widget_settings',
            array( $this, 'sanitize_widget_settings' )
        );

        register_setting(
            'magic_square_widget_style_options',
            'magic_square_widget_style',
            array( $this, 'sanitize_style_settings' )
        );

        register_setting(
            'magic_square_widget_code_options',
            'magic_square_widget_code',
            array( $this, 'sanitize_code_settings' )
        );
    }

    public function sanitize_widget_settings( $input ) {
        $sanitized = array();
        // ID sabitlendi, kullanıcı değiştiremez.
        $sanitized['id']          = 'metatronslove';
        $sanitized['color']       = sanitize_hex_color( $input['color'] );
        $sanitized['position']    = in_array( $input['position'], array( 'left', 'right' ) ) ? $input['position'] : 'right';
        $sanitized['margin_x']    = intval( $input['margin_x'] );
        $sanitized['margin_y']    = intval( $input['margin_y'] );
        $sanitized['message']     = sanitize_text_field( $input['message'] );
        $sanitized['description'] = sanitize_text_field( $input['description'] );
        $sanitized['enabled']     = isset( $input['enabled'] ) ? 1 : 0;

        $sanitized['button_type']   = in_array( $input['button_type'], array( 'emoji', 'svg', 'png' ) ) ? $input['button_type'] : 'emoji';
        $sanitized['button_emoji']  = sanitize_text_field( $input['button_emoji'] );
        $sanitized['button_svg']    = wp_kses_post( $input['button_svg'] );
        $sanitized['button_png_url'] = esc_url_raw( $input['button_png_url'] );

        return $sanitized;
    }

    public function sanitize_style_settings( $input ) {
        return array(
            'custom_css' => wp_kses_post( $input['custom_css'] )
        );
    }

    public function sanitize_code_settings( $input ) {
        return array(
            'custom_js' => $input['custom_js']
        );
    }
}
