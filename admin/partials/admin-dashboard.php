<?php
/**
 * Dashboard tab
 *
 * @package MagicSquareWidget
 * @subpackage Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$magic_square_site_url    = get_site_url();
$magic_square_version     = MAGIC_SQUARE_WIDGET_VERSION;
$magic_square_user_id     = get_current_user_id();
$magic_square_use_external = get_user_meta( $magic_square_user_id, 'magic_square_use_external', true );

if ( $magic_square_use_external === '' ) {
    $magic_square_use_external = 1; // Default: external on
}

if ( isset( $_POST['magic_square_dashboard_nonce'] ) ) {
    $magic_square_nonce = sanitize_key( wp_unslash( $_POST['magic_square_dashboard_nonce'] ) );
    if ( wp_verify_nonce( $magic_square_nonce, 'magic_square_dashboard_pref' ) ) {
        $magic_square_new_value = isset( $_POST['magic_square_use_external'] ) ? intval( $_POST['magic_square_use_external'] ) : 0;
        update_user_meta( $magic_square_user_id, 'magic_square_use_external', $magic_square_new_value );
        $magic_square_use_external = $magic_square_new_value;
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Preference saved.', 'magicsquare-widget' ) . '</p></div>';
    }
}

$magic_square_widget_dashboard_url = "https://one.fanclub.rocks/widgets/dashboard.php?site=" . urlencode($magic_square_site_url) . "&widget=magicsquare-widget&version=" . urlencode($magic_square_version);
?>

<div class="wrap magic-dashboard-page">
    <h1><?php esc_html_e( 'Magic Square Widget Dashboard', 'magicsquare-widget' ); ?></h1>

    <form method="post" action="" style="margin-bottom: 20px;">
        <?php wp_nonce_field( 'magic_square_dashboard_pref', 'magic_square_dashboard_nonce' ); ?>
        <label>
            <input type="checkbox" name="magic_square_use_external" value="1" <?php checked( $magic_square_use_external, 1 ); ?>>
            <?php esc_html_e( 'Use external dashboard', 'magicsquare-widget' ); ?>
        </label>
        <p class="description"><?php esc_html_e( 'Uncheck to see local content instead of the remote dashboard.', 'magicsquare-widget' ); ?></p>
        <?php submit_button( esc_html__( 'Save Preference', 'magicsquare-widget' ), 'secondary', 'submit', false ); ?>
    </form>

    <?php if ( $magic_square_use_external ) : ?>
        <div class="notice notice-info">
            <p>
                <?php esc_html_e( 'The dashboard is hosted externally.', 'magicsquare-widget' ); ?>
                <a href="<?php echo esc_url( $magic_square_widget_dashboard_url ); ?>" target="_blank" class="button button-primary">
                    <?php esc_html_e( 'Open External Dashboard', 'magicsquare-widget' ); ?>
                </a>
            </p>
        </div>
    <?php endif; ?>

    <div class="magic-dashboard-container">
        <div class="magic-dashboard-header">
            <p>
                <?php esc_html_e( 'Welcome! You can follow widget news and announcements from this panel.', 'magicsquare-widget' ); ?>
            </p>
        </div>

        <div class="magic-dashboard-content">
            <?php include_once 'dashboard-local.php'; ?>
        </div>
    </div>
</div>

<style>
.magic-dashboard-page {
    margin: 20px 20px 0 2px;
}

.magic-dashboard-container {
    background: #fff;
    border: 1px solid #ccd0d4;
    box-shadow: 0 1px 1px rgba(0,0,0,.04);
    margin-top: 20px;
}

.magic-dashboard-header {
    padding: 15px 20px;
    border-bottom: 1px solid #ccd0d4;
    background: #f8f9fa;
}

.magic-dashboard-header p {
    margin: 0;
    font-size: 14px;
}

.magic-dashboard-content {
    min-height: 500px;
    position: relative;
    background: #fff;
    padding: 20px;
}
</style>
