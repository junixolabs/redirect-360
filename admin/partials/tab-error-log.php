<?php
defined( 'ABSPATH' ) || exit;

if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( esc_html__( 'Access denied.', 'redirect-360' ) );
}

$logs = R360_404_Logs::get_logs();

// Handle clear.
if ( isset( $_POST['clear_404_logs'] ) && check_admin_referer( 'r360_clear_404_logs' ) ) {
    R360_404_Logs::clear_logs();
    echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( '404 Logs cleared.', 'redirect-360' ) . '</p></div>';
    $logs = array(); // Immediately reflect cleared state
}
?>

<div class="flex flex-wrap -mx-4">
    <div class="w-3/4 px-4">
        <table class="w-full bg-white border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2 text-left"><?php esc_html_e( 'Target URL', 'redirect-360' ); ?></th>
                    <th class="p-2 text-left"><?php esc_html_e( 'Last Hit Date/Time', 'redirect-360' ); ?></th>
                    <th class="p-2 text-left"><?php esc_html_e( 'Total Hits', 'redirect-360' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if ( empty( $logs ) ) : ?>
                <tr>
                    <td colspan="3" class="p-2 text-center"><?php esc_html_e( 'No 404 logs.', 'redirect-360' ); ?></td>
                </tr>
                <?php else : ?>
                <?php foreach ( $logs as $log ) : ?>
                <tr>
                    <td class="p-2"><?php echo esc_html( $log['requested_url'] ); ?></td>
                    <td class="p-2">
                        <?php echo esc_html( date_i18n( 'Y-m-d H:i:s', strtotime( $log['last_hit'] ) ) ); ?>
                    </td>
                    <td class="p-2"><?php echo esc_html( $log['hit_count'] ); ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <form method="post" class="mt-4">
            <?php wp_nonce_field( 'r360_clear_404_logs' ); ?>
            <button type="submit" name="clear_404_logs" class="bg-red-500 text-white p-2 rounded"
                onclick="return confirm('<?php esc_attr_e( 'Clear all 404 logs?', 'redirect-360' ); ?>');">
                <?php esc_html_e( 'Clear Logs', 'redirect-360' ); ?>
            </button>
        </form>
    </div>

    <!-- Support sidebar -->
    <div class="w-1/4 px-4">
        <div class="border-2 border-blue-600 bg-white p-6 shadow-sm">
            <!-- Header -->
            <div class="text-center">
                <h2 class="text-2xl font-bold text-blue-700">
                    <?php esc_html_e( 'Redirect 360', 'redirect-360' ); ?>
                </h2>
                <p class="mt-2 text-sm text-slate-600">
                    <?php esc_html_e( 'Free & Open-Source WordPress Redirection Plugin', 'redirect-360' ); ?>
                </p>
            </div>

            <!-- Divider -->
            <div class="my-4 h-px bg-blue-100"></div>

            <!-- Features -->
            <ul class="space-y-2 text-sm text-slate-700">
                <li>• <?php esc_html_e( 'Internal & External URL Redirects', 'redirect-360' ); ?></li>
                <li>• <?php esc_html_e( 'Chart-based Redirect Analytics', 'redirect-360' ); ?></li>
                <li>• <?php esc_html_e( 'Bulk Import & Export (CSV)', 'redirect-360' ); ?></li>
                <li>• <?php esc_html_e( 'Easy Redirect Management Dashboard', 'redirect-360' ); ?></li>
                <li>• <?php esc_html_e( 'Actively Maintained Open-Source Project', 'redirect-360' ); ?></li>
                <li>• <?php esc_html_e( 'Community-Driven Development', 'redirect-360' ); ?></li>
            </ul>

            <!-- CTA -->
            <div class="mt-6 text-center">
                <a href="<?php echo esc_url( 'https://buymeacoffee.com/shubhadipbhowmik' ); ?>" target="_blank"
                    rel="noopener" class="inline-block w-full bg-blue-600 px-4 py-3 text-sm font-semibold text-white"
                    style="color: #ffffff !important">
                    ☕ <?php esc_html_e( 'Support Development', 'redirect-360' ); ?>
                </a>

                <p class="mt-2 text-xs text-slate-500">
                    <?php esc_html_e( 'Optional support. No paywalls. Ever.', 'redirect-360' ); ?>
                </p>
            </div>
        </div>
    </div>
</div>