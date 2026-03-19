<?php
/**
 * Magic Square Widget Public Class
 *
 * Handles the public-facing functionality of the widget.
 *
 * @package MagicSquareWidget
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Magic_Square_Widget_Public {

    private $plugin_name;
    private $version;

    public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Returns an associative array of all translation strings used in the widget.
     */
    private function get_translation_strings() {
        return array(
            // Tab labels
            'tabText'                    => __( 'Text', 'magicsquare-widget' ),
            'tabTabled'                  => __( 'Tabled Text', 'magicsquare-widget' ),
            'tabHtml'                     => __( 'HTML', 'magicsquare-widget' ),
            'tabPdf'                      => __( 'PDF', 'magicsquare-widget' ),
            'tabPng'                      => __( 'PNG', 'magicsquare-widget' ),
            'tabSupport'                   => __( 'Support', 'magicsquare-widget' ),

            // Controls - Left panel
            'squareSize'                   => __( 'Square Size:', 'magicsquare-widget' ),
            'rowSum'                       => __( 'Row Sum:', 'magicsquare-widget' ),
            'algorithm'                    => __( 'Algorithm:', 'magicsquare-widget' ),
            'generate'                     => __( 'Generate', 'magicsquare-widget' ),
            'rotate'                       => __( 'Rotate', 'magicsquare-widget' ),
            'mirror'                       => __( 'Mirror', 'magicsquare-widget' ),
            'keepPushed'                   => __( 'Keep pushed', 'magicsquare-widget' ),
            'arabToIndian'                  => __( 'Use Indian Numbers', 'magicsquare-widget' ),

            // Text tab
            'tabTextDesc'                  => __( 'Tab-separated values for spreadsheets.', 'magicsquare-widget' ),

            // Tabled tab
            'boxBorders'                    => __( 'Box borders:', 'magicsquare-widget' ),
            'cellHeight'                    => __( 'Cell height:', 'magicsquare-widget' ),
            'cellWidth'                     => __( 'Cell width:', 'magicsquare-widget' ),

            // HTML tab
            'inkColor'                      => __( 'Ink color:', 'magicsquare-widget' ),
            'rotationStart'                 => __( 'Rotation start direction', 'magicsquare-widget' ),
            'left'                           => __( 'Left (-45°)', 'magicsquare-widget' ),
            'right'                          => __( 'Right (+45°)', 'magicsquare-widget' ),
            'none'                           => __( 'No rotation', 'magicsquare-widget' ),

            // PDF&PNG tab
            'paperSize'                      => __( 'Paper size:', 'magicsquare-widget' ),
            'dreamSize'                      => __( 'Fit to content', 'magicsquare-widget' ),
            'a5Portrait'                     => __( 'A5 Portrait', 'magicsquare-widget' ),
            'a4Portrait'                     => __( 'A4 Portrait', 'magicsquare-widget' ),
            'a3Portrait'                     => __( 'A3 Portrait', 'magicsquare-widget' ),
            'a5Landscape'                    => __( 'A5 Landscape', 'magicsquare-widget' ),
            'a4Landscape'                    => __( 'A4 Landscape', 'magicsquare-widget' ),
            'a3Landscape'                    => __( 'A3 Landscape', 'magicsquare-widget' ),

            // Output area (ortak)
            'magicSquare'                    => __( 'Magic Square:', 'magicsquare-widget' ),
            'copyToClipboard'                 => __( 'Copy to Clipboard', 'magicsquare-widget' ),
            'saveFile'                        => __( 'Save File', 'magicsquare-widget' ),
            'copySuccess'                     => __( 'Copied to clipboard!', 'magicsquare-widget' ),
            'saveSuccess'                     => __( 'File saved!', 'magicsquare-widget' ),
            'sizeError'                       => __( 'Please enter 3 or greater.', 'magicsquare-widget' ),

            // Algorithm options
            'siamese'                        => __( 'Odd sized (Siamese)', 'magicsquare-widget' ),
            'stracheyDouble'                 => __( 'Doubly even (Strachey)', 'magicsquare-widget' ),
            'durer'                           => __( 'Doubly even (Durer)', 'magicsquare-widget' ),
            'simpleExchange'                  => __( 'Doubly even (Simple exchange)', 'magicsquare-widget' ),
            'stracheySingle'                  => __( 'Singly even (Strachey)', 'magicsquare-widget' ),

            // Result messages
            'magicConstant'                   => __( 'Magic Constant:', 'magicsquare-widget' ),
            'row'                              => __( 'Row', 'magicsquare-widget' ),
            'column'                           => __( 'Column', 'magicsquare-widget' ),
            'mainDiagonal'                     => __( 'Main Diagonal:', 'magicsquare-widget' ),
            'sideDiagonal'                     => __( 'Side Diagonal:', 'magicsquare-widget' ),
            'total'                            => __( 'Total', 'magicsquare-widget' ),
            'magicSquareValid'                 => __( 'Magic square is valid!', 'magicsquare-widget' ),
            'magicSquareInvalid'                => __( 'Magic square is NOT valid!', 'magicsquare-widget' ),

            // Switch titles (hover için)
            'titleKeepPushed'                  => __( 'Keep pushed - auto generate on change', 'magicsquare-widget' ),
            'titleIndianNumbers'                => __( 'Switch between Arabic and Indian numbers', 'magicsquare-widget' ),

            // BMC Note
            'bmcNote'                         => __( 'Thank you for your support!', 'magicsquare-widget' ),
        );
    }

    /**
     * Outputs the widget script in the footer.
     */
    public function output_widget_script() {
        $options = get_option( 'magic_square_widget_settings' );
        if ( empty( $options['enabled'] ) ) {
            return;
        }

        $plugin_url = trailingslashit( plugin_dir_url( __FILE__ ) );

        // Enqueue html2canvas and jspdf
        wp_enqueue_script(
            $this->plugin_name . '-html2canvas',
            $plugin_url . 'js/html2canvas.min.js',
            array( 'jquery' ),
            $this->version,
            true
        );

        wp_enqueue_script(
            $this->plugin_name . '-jspdf',
            $plugin_url . 'js/jspdf.umd.min.js',
            array( 'jquery' ),
            $this->version,
            true
        );

        // Dynamic JS via AJAX - ÖNCE YÜKLENİR (widgetId'yi tanımlar)
        $ajax_url = admin_url( 'admin-ajax.php?action=magic_square_widget_js' );
        if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
            $ajax_url .= '&min=1';
        }

        wp_enqueue_script(
            $this->plugin_name . '-widget',
            $ajax_url,
            array( 'jquery', $this->plugin_name . '-html2canvas', $this->plugin_name . '-jspdf' ),
            $this->version,
            true
        );

        // Localize script with translations and config
        wp_localize_script( $this->plugin_name . '-widget', 'magicSquareData', array(
            'i18n'    => $this->get_translation_strings(),
            'config'  => array(
                'id'            => 'metatronslove',
                'color'         => esc_attr( $options['color'] ),
                'position'      => esc_attr( $options['position'] ),
                'margin_x'      => intval( $options['margin_x'] ),
                'margin_y'      => intval( $options['margin_y'] ),
                'message'       => esc_attr( $options['message'] ),
                'description'   => esc_attr( $options['description'] ),
                'button_type'   => isset( $options['button_type'] ) ? esc_attr( $options['button_type'] ) : 'emoji',
                'button_emoji'  => isset( $options['button_emoji'] ) ? esc_attr( $options['button_emoji'] ) : '🪄',
                'button_svg'    => isset( $options['button_svg'] ) ? $options['button_svg'] : '',
                'button_png_url' => isset( $options['button_png_url'] ) ? esc_url( $options['button_png_url'] ) : '',
                'pluginUrl'     => $plugin_url
            )
        ) );
    }
}
