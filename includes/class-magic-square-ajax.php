<?php
/**
 * AJAX handler for Magic Square Widget.
 *
 * @package MagicSquareWidget
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Magic_Square_Widget_Ajax {

    public function __construct() {
        // Hem giriş yapmış hem de misafir kullanıcılar için
        add_action( 'wp_ajax_magic_square_widget_js', array( $this, 'generate_js' ) );
        add_action( 'wp_ajax_nopriv_magic_square_widget_js', array( $this, 'generate_js' ) );
    }

    public function generate_js() {
        // JavaScript header'ı gönder
        header( 'Content-Type: application/javascript; charset=UTF-8' );

        // Ayarları oku
        $widget_options = get_option( 'magic_square_widget_settings', array() );
        $style_options  = get_option( 'magic_square_widget_style', array() );
        $code_options   = get_option( 'magic_square_widget_code', array() );

        // JavaScript kodunu oluştur ve çıktıla
        include_once plugin_dir_path( __FILE__ ) . '../public/magicsquare-js.php';

        wp_die(); // Ajax işlemini sonlandır
    }
}
