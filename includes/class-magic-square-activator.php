<?php
/**
 * Fired during plugin activation.
 *
 * @package MagicSquareWidget
 */

class Magic_Square_Widget_Activator {

    public static function activate() {
        // Widget ayarları - Yeni alanlar eklendi
        $default_widget = array(
            'id'                => 'metatronslove', // Sabitlendi
            'color'             => '#FFDD00',
            'position'          => 'right', // 'left' veya 'right'
            'margin_x'          => 18,       // Yatay mesafe (px)
            'margin_y'          => 18,       // Dikey mesafe (px)
            'message'           => 'Like my projects? Buy me a coffee!',
            'description'       => 'Support my work on magic squares',
            'enabled'           => 1,
            'button_type'       => 'emoji',
            'button_emoji'      => '🪄',
            'button_svg'        => '',
            'button_png_url'    => ''
        );

        // Stil ayarları
        $default_style = array('custom_css' => '');

        // Kod ayarları
        $default_code = array('custom_js' => '');

        // Sadece yoksa ekle
        if ( false === get_option( 'magic_square_widget_settings' ) ) {
            update_option( 'magic_square_widget_settings', $default_widget );
        }
        if ( false === get_option( 'magic_square_widget_style' ) ) {
            update_option( 'magic_square_widget_style', $default_style );
        }
        if ( false === get_option( 'magic_square_widget_code' ) ) {
            update_option( 'magic_square_widget_code', $default_code );
        }
    }
}
