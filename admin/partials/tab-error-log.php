<?php
defined( 'ABSPATH' ) || exit;

if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( 'Access denied.' );
}

$logs = Redirect_360_404_Logs::get_logs();

// Handle clear.
if ( isset( $_POST['clear_404_logs'] ) && check_admin_referer( 'clear_404_logs' ) ) {
    Redirect_360_404_Logs::clear_logs();
    echo '<div class="notice notice-success"><p>404 Logs cleared.</p></div>';
    $logs = array();
}
?>

<div class="flex flex-wrap -mx-4">
    <div class="w-3/4 px-4">
        <table class="w-full bg-white border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2 text-left">Target URL</th>
                    <th class="p-2 text-left">Last Hit Date/Time</th>
                    <th class="p-2 text-left">Total Hits</th>
                </tr>
            </thead>
            <tbody>
                <?php if ( empty( $logs ) ) : ?>
                <tr>
                    <td colspan="3" class="p-2 text-center">No 404 logs.</td>
                </tr>
                <?php else : ?>
                <?php foreach ( $logs as $log ) : ?>
                <tr>
                    <td class="p-2"><?php echo esc_html( $log['requested_url'] ); ?></td>
                    <td class="p-2"><?php echo date( 'Y-m-d H:i:s', strtotime( $log['last_hit'] ) ); ?></td>
                    <td class="p-2"><?php echo $log['hit_count']; ?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <form method="post" class="mt-4">
            <?php wp_nonce_field( 'clear_404_logs' ); ?>
            <button type="submit" name="clear_404_logs" class="bg-red-500 text-white p-2"
                onclick="return confirm('Clear all 404 logs?');">Clear Logs</button>
        </form>
    </div>

    <!-- support section right  -->
    <!-- support section  -->
    <div class="w-1/4 px-4">
        <div class="border-2 border-blue-600 bg-white p-6 shadow-sm">

            <!-- Header -->
            <div class="text-center">
                <h2 class="text-2xl font-bold text-blue-700">
                    Redirect 360
                </h2>
                <p class="mt-2 text-sm text-slate-600">
                    Free & Open-Source WordPress Redirection Plugin
                </p>
            </div>

            <!-- Divider -->
            <div class="my-4 h-px bg-blue-100"></div>

            <!-- Features -->
            <ul class="space-y-2 text-sm text-slate-700">
                <li>• Internal & External URL Redirects</li>
                <li>• Chart-based Redirect Analytics</li>
                <li>• Bulk Import & Export (CSV)</li>
                <li>• Easy Redirect Management Dashboard</li>
                <li>• Actively Maintained Open-Source Project</li>
                <li>• Community-Driven Development</li>

            </ul>

            <!-- CTA -->
            <div class="mt-6 text-center">
                <a href="https://buymeacoffee.com/shubhadipbhowmik" target="_blank"
                    class="inline-block w-full bg-blue-600 px-4 py-3 text-sm font-semibold text-white"
                    style="color: #ffffff !important">
                    ☕ Support Development
                </a>

                <p class="mt-2 text-xs text-slate-500">
                    Optional support. No paywalls. Ever.
                </p>
            </div>

        </div>
    </div>


</div>