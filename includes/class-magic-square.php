<?php
/**
 * The core plugin class.
 *
 * @package MagicSquareWidget
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Magic_Square_Widget {

    protected $loader;
    protected $plugin_name;
    protected $version;

    public function __construct() {
        $this->plugin_name = 'magicsquare-widget';
        $this->version = MAGIC_SQUARE_WIDGET_VERSION;

        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    private function load_dependencies() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-magic-square-loader.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-magic-square-admin.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-magic-square-public.php';

        $this->loader = new Magic_Square_Widget_Loader();
    }

    private function define_admin_hooks() {
        $plugin_admin = new Magic_Square_Widget_Admin( $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action( 'wp_ajax_magic_square_widget_js', $plugin_admin, 'generate_widget_js' );
        $this->loader->add_action( 'wp_ajax_nopriv_magic_square_widget_js', $plugin_admin, 'generate_widget_js' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );
    }

    private function define_public_hooks() {
        $plugin_public = new Magic_Square_Widget_Public( $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action( 'wp_footer', $plugin_public, 'output_widget_script' );
    }

    public function run() {
        $this->loader->run();
    }

    public function get_plugin_name() {
        return $this->plugin_name;
    }

    public function get_version() {
        return $this->version;
    }
}
