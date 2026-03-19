<?php
/**
 * Local dashboard content
 *
 * @package MagicSquareWidget
 * @subpackage Admin
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

$magic_square_version = MAGIC_SQUARE_WIDGET_VERSION;
$magic_square_bmc_id  = get_option( 'magic_square_widget_settings', array( 'id' => 'metatronslove' ) )['id'];
?>
<div class="magic-local-dashboard">
    <h2><?php esc_html_e( 'Magic Square Widget - Dashboard', 'magicsquare-widget' ); ?></h2>
    <p><?php esc_html_e( 'Welcome! You can manage widget settings from the Settings page.', 'magicsquare-widget' ); ?></p>
    <p><?php esc_html_e( 'Version:', 'magicsquare-widget' ); ?> <strong><?php echo esc_html( $magic_square_version ); ?></strong></p>

    <hr>

    <h3><?php esc_html_e( 'About Magic Squares', 'magicsquare-widget' ); ?></h3>
    <p><?php esc_html_e( 'A magic square is an arrangement of distinct numbers in a square grid, where the numbers in each row, column, and both main diagonals all add up to the same total.', 'magicsquare-widget' ); ?></p>
    <p><?php esc_html_e( 'This widget generates magic squares of any size (n ≥ 3) using various classical algorithms like the Siamese method, Strachey method, and Dürer\'s method.', 'magicsquare-widget' ); ?></p>

    <h3><?php esc_html_e( 'Use Cases', 'magicsquare-widget' ); ?></h3>
    <ul>
        <li><strong><?php esc_html_e( 'Education:', 'magicsquare-widget' ); ?></strong> <?php esc_html_e( 'Teach mathematical concepts and number patterns.', 'magicsquare-widget' ); ?></li>
        <li><strong><?php esc_html_e( 'Recreational Mathematics:', 'magicsquare-widget' ); ?></strong> <?php esc_html_e( 'Explore the fascinating world of magic squares.', 'magicsquare-widget' ); ?></li>
        <li><strong><?php esc_html_e( 'Web Design:', 'magicsquare-widget' ); ?></strong> <?php esc_html_e( 'Create visually appealing and interactive content for your site.', 'magicsquare-widget' ); ?></li>
        <li><strong><?php esc_html_e( 'Art & Design:', 'magicsquare-widget' ); ?></strong> <?php esc_html_e( 'Generate patterns for artistic projects.', 'magicsquare-widget' ); ?></li>
    </ul>

    <hr>

    <h3><?php esc_html_e( '☕ Support the Project', 'magicsquare-widget' ); ?></h3>
    <p><?php esc_html_e( 'If you like my project, you can buy me a coffee!', 'magicsquare-widget' ); ?></p>
    <p>
        <a href="https://www.buymeacoffee.com/<?php echo esc_attr( $magic_square_bmc_id ); ?>" target="_blank" class="button button-primary">
            <?php esc_html_e( 'Buy Me A Coffee', 'magicsquare-widget' ); ?>
        </a>
    </p>
    <p><?php esc_html_e( 'Thank you! 🙏', 'magicsquare-widget' ); ?></p>
</div>

<style>
.magic-local-dashboard {
    padding: 20px;
    background: #fff;
}
.magic-local-dashboard h3 {
    margin-top: 25px;
    color: #23282d;
    font-size: 16px;
    font-weight: 600;
}
.magic-local-dashboard hr {
    margin: 20px 0;
    border: 0;
    border-top: 1px solid #ccc;
}
.magic-local-dashboard ul {
    list-style: disc;
    margin-left: 20px;
}
</style>
