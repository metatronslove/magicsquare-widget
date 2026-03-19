<?php
/**
 * Plugin Name:       Magic Square Widget
 * Plugin URI:        https://one.fanclub.rocks/wordpress-magicsquare-widget
 * Description:       Sitenizin köşesine özelleştirilebilir bir sihirli kare oluşturucu (widget) ekler.
 * Version:           1.0.0
 * Author:            Metatron's Love
 * Author URI:        https://one.fanclub.rocks
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       magicsquare-widget
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

define( 'MAGIC_SQUARE_WIDGET_VERSION', '1.0.0' );

/**
 * Activate the plugin.
 */
function magic_square_widget_activate() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-magic-square-activator.php';
    Magic_Square_Widget_Activator::activate();
}

/**
 * Deactivate the plugin.
 */
function magic_square_widget_deactivate() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-magic-square-deactivator.php';
    Magic_Square_Widget_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'magic_square_widget_activate' );
register_deactivation_hook( __FILE__, 'magic_square_widget_deactivate' );

require plugin_dir_path( __FILE__ ) . 'includes/class-magic-square.php';

/**
 * Run the plugin.
 */
function magic_square_widget_run() {
    $plugin = new Magic_Square_Widget();
    $plugin->run();
}
magic_square_widget_run();
