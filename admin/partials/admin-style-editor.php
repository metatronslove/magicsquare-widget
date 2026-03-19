<?php
/**
 * Style editor tab
 *
 * @package MagicSquareWidget
 * @subpackage Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$magic_square_style_options = get_option( 'magic_square_widget_style', array( 'custom_css' => '' ) );

// Extract default CSS from the public file for the reset button.
$magic_square_default_css_file = plugin_dir_path( dirname( __FILE__ ) ) . 'public/magicsquare-js.php';
$magic_square_default_css = '';
if ( file_exists( $magic_square_default_css_file ) ) {
    $magic_square_content = file_get_contents( $magic_square_default_css_file );
    preg_match( '/style\.textContent = `(.*?)`;/s', $magic_square_content, $matches );
    if ( isset( $matches[1] ) ) {
        $magic_square_default_css = $matches[1];
    }
}
?>

<div class="wrap magic-style-editor-page">
    <h1><?php esc_html_e( 'Style Editor', 'magicsquare-widget' ); ?></h1>

    <div class="notice notice-warning">
        <p>
            <strong><?php esc_html_e( 'WARNING', 'magicsquare-widget' ); ?>:</strong>
            <?php esc_html_e( 'Your changes will directly affect the widget appearance. If you don\'t know CSS, it\'s recommended to use default settings.', 'magicsquare-widget' ); ?>
        </p>
    </div>

    <div class="notice notice-info">
        <p>
            <?php esc_html_e( 'Add custom CSS to customize the widget appearance. This CSS will be applied after the default widget styles, so you can override any rule.', 'magicsquare-widget' ); ?>
        </p>
        <p>
            <?php esc_html_e( 'Changes are visible immediately on the frontend after saving. No preview is available here because the widget is dynamic, but you can test on your site.', 'magicsquare-widget' ); ?>
        </p>
    </div>

    <form method="post" action="options.php">
        <?php settings_fields( 'magic_square_widget_style_options' ); ?>

        <div class="magic-editor-container">
            <div class="magic-editor-toolbar">
                <button type="button" class="button" id="reset-css">
                    <?php esc_html_e( 'Reset to Default', 'magicsquare-widget' ); ?>
                </button>
            </div>

            <div class="magic-editor-main">
                <textarea id="custom_css" 
                          name="magic_square_widget_style[custom_css]" 
                          class="large-text code" 
                          rows="20"><?php echo esc_textarea( $magic_square_style_options['custom_css'] ); ?></textarea>
            </div>

            <p class="description"><?php esc_html_e( 'Enter your custom CSS rules here. They will be injected into the widget.', 'magicsquare-widget' ); ?></p>
        </div>

        <?php submit_button( esc_html__( 'Save CSS', 'magicsquare-widget' ) ); ?>
    </form>
</div>

<style>
.magic-editor-container {
    margin: 20px 0;
}

.magic-editor-toolbar {
    margin-bottom: 10px;
    padding: 10px;
    background: #f8f9fa;
    border: 1px solid #ccd0d4;
    border-radius: 4px;
}

.magic-editor-toolbar .button {
    margin-right: 5px;
}

.CodeMirror {
    border: 1px solid #ccd0d4;
    height: auto;
    min-height: 400px;
    font-family: 'Courier New', monospace;
    font-size: 13px;
    line-height: 1.5;
}

.CodeMirror-gutters {
    background: #f8f9fa;
    border-right: 1px solid #ccd0d4;
}

.CodeMirror-linenumber {
    color: #666;
}
</style>

<script>
jQuery(document).ready(function($) {
    var editor = wp.codeEditor.initialize($('#custom_css'), {
        codemirror: {
            mode: 'css',
            lineNumbers: true,
            lineWrapping: true,
            theme: 'default',
            autoCloseBrackets: true,
            matchBrackets: true,
            indentUnit: 4,
            tabSize: 4,
            indentWithTabs: true,
            extraKeys: {
                'Ctrl-Space': 'autocomplete'
            }
        }
    });

    var cssEditor = editor.codemirror;

    $('#reset-css').on('click', function() {
        if (confirm('<?php echo esc_js( __( 'All your changes will be lost. Are you sure?', 'magicsquare-widget' ) ); ?>')) {
            var defaultCss = <?php echo wp_json_encode( $magic_square_default_css ); ?>;
            cssEditor.setValue(defaultCss);
        }
    });
});
</script>
