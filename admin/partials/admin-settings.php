<?php
/**
 * Settings tab
 *
 * @package MagicSquareWidget
 * @subpackage Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$magic_square_options = get_option( 'magic_square_widget_settings', array(
    'id'            => 'metatronslove',
    'color'         => '#FFDD00',
    'position'      => 'right',
    'margin_x'      => 18,
    'margin_y'      => 18,
    'message'       => 'Like my projects? Buy me a coffee!',
    'description'   => 'Support my work on magic squares',
    'enabled'       => 1,
    'button_type'   => 'emoji',
    'button_emoji'  => '🪄',
    'button_svg'    => '',
    'button_png_url' => ''
) );
?>

<div class="wrap magic-settings-page">
    <h1><?php esc_html_e( 'Widget Settings', 'magicsquare-widget' ); ?></h1>

    <form method="post" action="options.php">
        <?php settings_fields( 'magic_square_widget_options' ); ?>

        <div class="magic-settings-tabs">
            <h2 class="nav-tab-wrapper">
                <a href="#general" class="nav-tab nav-tab-active"><?php esc_html_e( 'General', 'magicsquare-widget' ); ?></a>
                <a href="#button" class="nav-tab"><?php esc_html_e( 'Button', 'magicsquare-widget' ); ?></a>
            </h2>

            <div class="magic-settings-tab-content" id="tab-general">
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Widget Status', 'magicsquare-widget' ); ?></th>
                        <td>
                            <label>
                                <input type="checkbox" name="magic_square_widget_settings[enabled]" value="1" <?php checked( $magic_square_options['enabled'], 1 ); ?> />
                                <?php esc_html_e( 'Widget active', 'magicsquare-widget' ); ?>
                            </label>
                            <p class="description"><?php esc_html_e( 'Check this box to activate the widget.', 'magicsquare-widget' ); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="magic_square_id"><?php esc_html_e( 'Buy Me a Coffee ID', 'magicsquare-widget' ); ?></label></th>
                        <td>
                            <input type="text"
                                   id="magic_square_id"
                                   name="magic_square_widget_settings[id]"
                                   value="<?php echo esc_attr( $magic_square_options['id'] ); ?>"
                                   class="regular-text" readonly disabled />
                            <p class="description"><?php esc_html_e( 'This ID is fixed to support the developer.', 'magicsquare-widget' ); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="magic_square_color"><?php esc_html_e( 'Button Color', 'magicsquare-widget' ); ?></label></th>
                        <td>
                            <input type="text"
                                   id="magic_square_color"
                                   name="magic_square_widget_settings[color]"
                                   value="<?php echo esc_attr( $magic_square_options['color'] ); ?>"
                                   class="regular-text color-picker" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Button Position', 'magicsquare-widget' ); ?></th>
                        <td>
                            <fieldset>
                                <label>
                                    <input type="radio" name="magic_square_widget_settings[position]" value="left" <?php checked( $magic_square_options['position'], 'left' ); ?> />
                                    <?php esc_html_e( 'Left', 'magicsquare-widget' ); ?>
                                </label><br>
                                <label>
                                    <input type="radio" name="magic_square_widget_settings[position]" value="right" <?php checked( $magic_square_options['position'], 'right' ); ?> />
                                    <?php esc_html_e( 'Right', 'magicsquare-widget' ); ?>
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="magic_square_margin_x"><?php esc_html_e( 'Horizontal Margin (px)', 'magicsquare-widget' ); ?></label></th>
                        <td>
                            <input type="number"
                                   id="magic_square_margin_x"
                                   name="magic_square_widget_settings[margin_x]"
                                   value="<?php echo esc_attr( $magic_square_options['margin_x'] ); ?>"
                                   class="small-text" min="0" step="1" />
                            <p class="description"><?php esc_html_e( 'Distance from the left or right edge.', 'magicsquare-widget' ); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="magic_square_margin_y"><?php esc_html_e( 'Vertical Margin (px)', 'magicsquare-widget' ); ?></label></th>
                        <td>
                            <input type="number"
                                   id="magic_square_margin_y"
                                   name="magic_square_widget_settings[margin_y]"
                                   value="<?php echo esc_attr( $magic_square_options['margin_y'] ); ?>"
                                   class="small-text" min="0" step="1" />
                            <p class="description"><?php esc_html_e( 'Distance from the bottom edge.', 'magicsquare-widget' ); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="magic_square_message"><?php esc_html_e( 'Button Message', 'magicsquare-widget' ); ?></label></th>
                        <td>
                            <input type="text"
                                   id="magic_square_message"
                                   name="magic_square_widget_settings[message]"
                                   value="<?php echo esc_attr( $magic_square_options['message'] ); ?>"
                                   class="regular-text" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="magic_square_description"><?php esc_html_e( 'Description', 'magicsquare-widget' ); ?></label></th>
                        <td>
                            <input type="text"
                                   id="magic_square_description"
                                   name="magic_square_widget_settings[description]"
                                   value="<?php echo esc_attr( $magic_square_options['description'] ); ?>"
                                   class="regular-text" />
                        </td>
                    </tr>
                </table>
            </div>

            <div class="magic-settings-tab-content" id="tab-button" style="display: none;">
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e( 'Button Type', 'magicsquare-widget' ); ?></th>
                        <td>
                            <fieldset>
                                <label>
                                    <input type="radio"
                                           name="magic_square_widget_settings[button_type]"
                                           value="emoji"
                                           <?php checked( $magic_square_options['button_type'], 'emoji' ); ?> />
                                    <?php esc_html_e( 'Emoji', 'magicsquare-widget' ); ?>
                                </label><br>

                                <label>
                                    <input type="radio"
                                           name="magic_square_widget_settings[button_type]"
                                           value="svg"
                                           <?php checked( $magic_square_options['button_type'], 'svg' ); ?> />
                                    <?php esc_html_e( 'SVG Code', 'magicsquare-widget' ); ?>
                                </label><br>

                                <label>
                                    <input type="radio"
                                           name="magic_square_widget_settings[button_type]"
                                           value="png"
                                           <?php checked( $magic_square_options['button_type'], 'png' ); ?> />
                                    <?php esc_html_e( 'PNG Image URL', 'magicsquare-widget' ); ?>
                                </label>
                            </fieldset>
                        </td>
                    </tr>

                    <tr class="button-option button-option-emoji">
                        <th scope="row"><label for="button_emoji"><?php esc_html_e( 'Button Emoji', 'magicsquare-widget' ); ?></label></th>
                        <td>
                            <input type="text"
                                   id="button_emoji"
                                   name="magic_square_widget_settings[button_emoji]"
                                   value="<?php echo esc_attr( $magic_square_options['button_emoji'] ); ?>"
                                   class="regular-text" />
                            <p class="description"><?php esc_html_e( 'Example: 🪄, ✨, 🧙', 'magicsquare-widget' ); ?></p>
                        </td>
                    </tr>

                    <tr class="button-option button-option-svg">
                        <th scope="row"><label for="button_svg"><?php esc_html_e( 'SVG Code', 'magicsquare-widget' ); ?></label></th>
                        <td>
                            <textarea id="button_svg"
                                      name="magic_square_widget_settings[button_svg]"
                                      rows="5"
                                      class="large-text code"><?php echo esc_textarea( $magic_square_options['button_svg'] ); ?></textarea>
                            <p class="description"><?php esc_html_e( 'Paste SVG code', 'magicsquare-widget' ); ?></p>
                        </td>
                    </tr>

                    <tr class="button-option button-option-png">
                        <th scope="row"><label for="button_png_url"><?php esc_html_e( 'PNG Image URL', 'magicsquare-widget' ); ?></label></th>
                        <td>
                            <input type="url"
                                   id="button_png_url"
                                   name="magic_square_widget_settings[button_png_url]"
                                   value="<?php echo esc_url( $magic_square_options['button_png_url'] ); ?>"
                                   class="regular-text" />
                            <p class="description"><?php esc_html_e( 'Enter image URL', 'magicsquare-widget' ); ?></p>

                            <?php if ( ! empty( $magic_square_options['button_png_url'] ) ) : ?>
                                <div style="margin-top: 10px;">
                                    <img src="<?php echo esc_url( $magic_square_options['button_png_url'] ); ?>"
                                         style="max-width: 100px; max-height: 100px; border: 1px solid #ddd; padding: 5px;" />
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <?php submit_button( esc_html__( 'Save Settings', 'magicsquare-widget' ) ); ?>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    $('.color-picker').wpColorPicker();

    $('.nav-tab-wrapper a').on('click', function(e) {
        e.preventDefault();
        var target = $(this).attr('href').replace('#', '');
        $('.nav-tab-wrapper a').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
        $('.magic-settings-tab-content').hide();
        $('#tab-' + target).show();
    });

    function toggleButtonOptions() {
        var selected = $('input[name="magic_square_widget_settings[button_type]"]:checked').val();
        $('.button-option').hide();
        $('.button-option-' + selected).show();
    }

    $('input[name="magic_square_widget_settings[button_type]"]').on('change', toggleButtonOptions);
    toggleButtonOptions();
});
</script>
