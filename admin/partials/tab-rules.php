<?php
defined( 'ABSPATH' ) || exit;

if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( esc_html__( 'Access denied.', 'redirect-360' ) );
}

// Handle add/update.
$id = isset( $_GET['edit'] ) ? absint( $_GET['edit'] ) : 0;
$redirect = $id ? R360_Redirects::get_redirects( $id ) : array( 'from_url' => '', 'to_url' => '', 'redirect_type' => 301 );

if ( isset( $_POST['submit_redirect'] ) && check_admin_referer( 'r360_save_redirect' ) ) {
    $data = array(
        'from_url'      => sanitize_text_field( $_POST['from_url'] ?? '' ),
        'to_url'        => esc_url_raw( $_POST['to_url'] ?? '' ),
        'redirect_type' => absint( $_POST['redirect_type'] ?? 301 ),
    );

    if ( $id ) {
        R360_Redirects::update_redirect( $id, $data );
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Redirect updated.', 'redirect-360' ) . '</p></div>';
    } else {
        R360_Redirects::add_redirect( $data );
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Redirect added.', 'redirect-360' ) . '</p></div>';
    }

    // Reset form after save
    $id       = 0;
    $redirect = array( 'from_url' => '', 'to_url' => '', 'redirect_type' => 301 );
}

// Handle delete.
if ( isset( $_GET['delete'] ) && check_admin_referer( 'r360_delete_redirect' ) ) {
    R360_Redirects::delete_redirect( absint( $_GET['delete'] ) );
    echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'Redirect deleted.', 'redirect-360' ) . '</p></div>';
}

$redirects = R360_Redirects::get_redirects();

$analytics_id = isset( $_GET['analytics_id'] ) ? absint( $_GET['analytics_id'] ) : null;
$hits_over_time = R360_Analytics::get_hits_over_time( $analytics_id );
$referrer_stats = R360_Analytics::get_referrer_stats( $analytics_id );

wp_localize_script( 'r360-admin-js', 'r360Data', array(
    'hitsOverTime'  => $hits_over_time,
    'referrerStats' => $referrer_stats,
) );
?>

<div class="flex flex-wrap -mx-4">
    <div class="w-3/4 px-4">
        <form method="post" class="bg-white p-4 mb-4">
            <?php wp_nonce_field( 'r360_save_redirect' ); ?>
            <div class="flex space-x-4">
                <div class="w-1/4">
                    <select name="redirect_type" class="w-full p-2 border">
                        <option value="301" <?php selected( $redirect['redirect_type'], 301 ); ?>>
                            <?php esc_html_e( '301 (Permanent)', 'redirect-360' ); ?></option>
                        <option value="302" <?php selected( $redirect['redirect_type'], 302 ); ?>>
                            <?php esc_html_e( '302 (Temporary)', 'redirect-360' ); ?></option>
                        <option value="307" <?php selected( $redirect['redirect_type'], 307 ); ?>>
                            <?php esc_html_e( '307 (Temporary)', 'redirect-360' ); ?></option>
                        <option value="410" <?php selected( $redirect['redirect_type'], 410 ); ?>>
                            <?php esc_html_e( '410 (Gone)', 'redirect-360' ); ?></option>
                    </select>
                </div>
                <div class="w-1/3">
                    <input type="text" name="from_url" value="<?php echo esc_attr( $redirect['from_url'] ); ?>"
                        placeholder="<?php esc_attr_e( 'Redirect From', 'redirect-360' ); ?>" class="w-full p-2 border"
                        required>
                </div>
                <div class="w-1/3">
                    <input type="text" name="to_url" value="<?php echo esc_attr( $redirect['to_url'] ); ?>"
                        placeholder="<?php esc_attr_e( 'Redirect To', 'redirect-360' ); ?>" class="w-full p-2 border"
                        required>
                </div>
                <div class="w-1/6">
                    <button type="submit" name="submit_redirect"
                        class="bg-[#2563eb] text-white p-2 rounded w-full"><?php esc_html_e( 'Save', 'redirect-360' ); ?></button>
                </div>
            </div>
        </form>

        <table class="w-full bg-white border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2 text-left"><?php esc_html_e( 'Redirect From', 'redirect-360' ); ?></th>
                    <th class="p-2 text-left"><?php esc_html_e( 'Redirect To', 'redirect-360' ); ?></th>
                    <th class="p-2 text-left"><?php esc_html_e( 'Hits', 'redirect-360' ); ?></th>
                    <th class="p-2 text-left"><?php esc_html_e( 'Actions', 'redirect-360' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if ( empty( $redirects ) ) : ?>
                <tr>
                    <td colspan="4" class="p-2 text-center"><?php esc_html_e( 'No redirects.', 'redirect-360' ); ?></td>
                </tr>
                <?php else : ?>
                <?php foreach ( $redirects as $r ) : ?>
                <tr>
                    <td class="p-2"><?php echo esc_html( $r['redirect_type'] . ' - ' . $r['from_url'] ); ?></td>
                    <td class="p-2"><?php echo esc_url( $r['to_url'] ); ?></td>
                    <td class="p-2"><?php echo esc_html( R360_Redirects::get_hits_count( $r['id'] ) ); ?></td>
                    <td class="p-2 whitespace-nowrap">
                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=r360&tab=rules&edit=' . absint( $r['id'] ) ) ); ?>"
                            class="text-blue-600 mr-2">
                            <?php esc_html_e( 'Edit', 'redirect-360' ); ?>
                        </a>

                        <a href="<?php echo esc_url( wp_nonce_url(
                                    admin_url( 'admin.php?page=r360&tab=rules&delete=' . absint( $r['id'] ) ),
                                    'r360_delete_redirect'
                                ) ); ?>" class="text-red-600 mr-2"
                            onclick="return confirm('<?php esc_attr_e( 'Sure?', 'redirect-360' ); ?>');">
                            <?php esc_html_e( 'Delete', 'redirect-360' ); ?>
                        </a>

                        <a href="<?php echo esc_url( admin_url( 'admin.php?page=r360&tab=rules&analytics_id=' . absint( $r['id'] ) ) ); ?>"
                            class="dashicons dashicons-chart-bar text-slate-500 align-middle"
                            title="<?php esc_attr_e( 'View Analytics', 'redirect-360' ); ?>">
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="mt-4 bg-white p-4 rounded shadow">
            <h2 class="text-xl">
                <?php
                echo $analytics_id
                    ? esc_html__( 'Analytics for Redirect ID ', 'redirect-360' ) . esc_html( $analytics_id )
                    : esc_html__( 'Overall Analytics', 'redirect-360' );
                ?>
            </h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h3><?php esc_html_e( 'Hits Over Time', 'redirect-360' ); ?></h3>
                    <canvas id="hitsOverTimeChart"></canvas>
                </div>
                <div>
                    <h3><?php esc_html_e( 'Referrers', 'redirect-360' ); ?></h3>
                    <canvas id="referrerStatsChart"></canvas>
                </div>
            </div>
        </div>
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