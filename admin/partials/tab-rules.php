<?php
defined( 'ABSPATH' ) || exit;

if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( 'Access denied.' );
}

// Handle add/update.
$id = isset( $_GET['edit'] ) ? intval( $_GET['edit'] ) : 0;
$redirect = $id ? Redirect_360_Redirects::get_redirects( $id ) : array( 'from_url' => '', 'to_url' => '', 'redirect_type' => 301 );

if ( isset( $_POST['submit_redirect'] ) && check_admin_referer( 'save_redirect' ) ) {
    $data = array(
        'from_url'      => $_POST['from_url'],
        'to_url'        => $_POST['to_url'],
        'redirect_type' => $_POST['redirect_type'],
    );
    if ( $id ) {
        Redirect_360_Redirects::update_redirect( $id, $data );
        echo '<div class="notice notice-success"><p>Redirect updated.</p></div>';
    } else {
        Redirect_360_Redirects::add_redirect( $data );
        echo '<div class="notice notice-success"><p>Redirect added.</p></div>';
    }
    $id = 0;  // Reset form.
    $redirect = array( 'from_url' => '', 'to_url' => '', 'redirect_type' => 301 );
}

// Handle delete.
if ( isset( $_GET['delete'] ) && check_admin_referer( 'delete_redirect' ) ) {
    Redirect_360_Redirects::delete_redirect( intval( $_GET['delete'] ) );
    echo '<div class="notice notice-success"><p>Redirect deleted.</p></div>';
}

$redirects = Redirect_360_Redirects::get_redirects();

$analytics_id = isset( $_GET['analytics_id'] ) ? intval( $_GET['analytics_id'] ) : null;
$hits_over_time = Redirect_360_Analytics::get_hits_over_time( $analytics_id );
$referrer_stats = Redirect_360_Analytics::get_referrer_stats( $analytics_id );

wp_localize_script( 'redirect-360-admin-js', 'redirect360Data', array(
    'hitsOverTime' => $hits_over_time,
    'referrerStats' => $referrer_stats,
) );
?>

<div class="flex flex-wrap -mx-4">
    <div class="w-3/4 px-4">
        <form method="post" class="bg-white p-4 mb-4 rounded shadow">
            <?php wp_nonce_field( 'save_redirect' ); ?>
            <div class="flex space-x-4">
                <div class="w-1/4">
                    <select name="redirect_type" class="w-full p-2 border">
                        <option value="301" <?php selected( $redirect['redirect_type'], 301 ); ?>>301 (Permanent)
                        </option>
                        <option value="302" <?php selected( $redirect['redirect_type'], 302 ); ?>>302 (Temporary)
                        </option>
                        <option value="307" <?php selected( $redirect['redirect_type'], 307 ); ?>>307 (Temporary)
                        </option>
                        <option value="410" <?php selected( $redirect['redirect_type'], 410 ); ?>>410 (Gone)</option>
                    </select>
                </div>
                <div class="w-1/3">
                    <input type="text" name="from_url" value="<?php echo esc_attr( $redirect['from_url'] ); ?>"
                        placeholder="Redirect From" class="w-full p-2 border" required>
                </div>
                <div class="w-1/3">
                    <input type="text" name="to_url" value="<?php echo esc_attr( $redirect['to_url'] ); ?>"
                        placeholder="Redirect To" class="w-full p-2 border" required>
                </div>
                <div class="w-1/6">
                    <button type="submit" name="submit_redirect"
                        class="bg-[#2563eb] text-white p-2 rounded w-full">Save</button>
                </div>
            </div>
        </form>

        <table class="w-full bg-white border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2 text-left">Redirect From</th>
                    <th class="p-2 text-left">Redirect To</th>
                    <th class="p-2 text-left">Hits</th>
                    <th class="p-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ( empty( $redirects ) ) : ?>
                <tr>
                    <td colspan="4" class="p-2 text-center">No redirects.</td>
                </tr>
                <?php else : ?>
                <?php foreach ( $redirects as $r ) : ?>
                <tr>
                    <td class="p-2"><?php echo $r['redirect_type'] . ' - ' . esc_html( $r['from_url'] ); ?></td>
                    <td class="p-2"><?php echo esc_html( $r['to_url'] ); ?></td>
                    <td class="p-2"><?php echo Redirect_360_Redirects::get_hits_count( $r['id'] ); ?></td>
                    <td class="p-2">
                        <a href="<?php echo admin_url( 'admin.php?page=redirect-360&tab=rules&edit=' . $r['id'] ); ?>"
                            class="text-blue-500">Edit</a>
                        <a href="<?php echo wp_nonce_url( admin_url( 'admin.php?page=redirect-360&tab=rules&delete=' . $r['id'] ), 'delete_redirect' ); ?>"
                            class="text-red-500" onclick="return confirm('Sure?');">Delete</a>
                        <a href="<?php echo admin_url( 'admin.php?page=redirect-360&tab=rules&analytics_id=' . $r['id'] ); ?>"
                            class="dashicons dashicons-chart-line text-gray-500"></a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="mt-4 bg-white p-4 rounded shadow">
            <h2 class="text-xl">
                <?php echo $analytics_id ? 'Analytics for Redirect ID ' . $analytics_id : 'Overall Analytics'; ?></h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h3>Hits Over Time</h3>
                    <canvas id="hitsOverTimeChart"></canvas>
                </div>
                <div>
                    <h3>Referrers</h3>
                    <canvas id="referrerStatsChart"></canvas>
                </div>
            </div>
        </div>
    </div>


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