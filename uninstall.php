<?php
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}
delete_option('magic_square_widget_settings');
delete_option('magic_square_widget_style');
delete_option('magic_square_widget_code');
