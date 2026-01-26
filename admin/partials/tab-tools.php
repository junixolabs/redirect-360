<?php
defined( 'ABSPATH' ) || exit;

if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( esc_html__( 'Access denied.', 'redirect-360' ) );
}

// Handle import.
$import_result = null;
if ( isset( $_POST['import_submit'] ) && check_admin_referer( 'r360_import_csv' ) && isset( $_FILES['csv_file'] ) ) {
    $mode = isset( $_POST['duplicate_mode'] ) ? sanitize_key( $_POST['duplicate_mode'] ) : 'skip';
    $import_result = R360_Importer::import_csv( $_FILES['csv_file'], $mode );
}
?>

<div class="flex flex-wrap -mx-4">
    <div class="w-3/4 px-4">
        <div class="bg-white p-4 mb-4 rounded shadow">
            <h2 class="text-lg font-bold"><?php esc_html_e( 'Import Redirect Rules (CSV)', 'redirect-360' ); ?></h2>
            <form method="post" enctype="multipart/form-data" class="space-y-4">
                <?php wp_nonce_field( 'r360_import_csv' ); ?>

                <!-- File Upload -->
                <div>
                    <div class="flex items-center gap-3">
                        <!-- Hidden native input -->
                        <input type="file" name="csv_file" accept=".csv" required id="csvFile" class="hidden">

                        <!-- Custom button -->
                        <label for="csvFile"
                            class="cursor-pointer bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 border border-slate-300 hover:bg-slate-200 transition">
                            <?php esc_html_e( 'Choose CSV File', 'redirect-360' ); ?>
                        </label>

                        <!-- File name -->
                        <span id="fileName" class="text-sm text-slate-500">
                            <?php esc_html_e( 'No file selected', 'redirect-360' ); ?>
                        </span>
                    </div>
                </div>

                <!-- Duplicate Mode -->
                <div class="flex gap-6 text-sm text-slate-700">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="duplicate_mode" value="skip" checked>
                        <?php esc_html_e( 'Skip Duplicates', 'redirect-360' ); ?>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="duplicate_mode" value="update">
                        <?php esc_html_e( 'Update Duplicates', 'redirect-360' ); ?>
                    </label>
                </div>

                <!-- Helper Text -->
                <div class="bg-slate-50 border border-slate-200 p-3 text-sm">
                    <p class="mb-2 font-medium text-slate-700">
                        <?php esc_html_e( 'CSV must contain these columns (in order):', 'redirect-360' ); ?>
                    </p>

                    <div class="flex gap-2 font-mono text-xs">
                        <span class="bg-blue-100 text-blue-700 px-2 py-1">
                            status_code
                        </span>
                        <span class="bg-blue-100 text-blue-700 px-2 py-1">
                            from_url
                        </span>
                        <span class="bg-blue-100 text-blue-700 px-2 py-1">
                            redirect_url
                        </span>
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit" name="import_submit"
                    class="bg-[#2563eb] px-5 py-2.5 text-sm cursor-pointer font-semibold text-white shadow hover:bg-blue-700 transition">
                    <?php esc_html_e( 'Upload CSV', 'redirect-360' ); ?>
                </button>
            </form>

            <?php if ( $import_result ) : ?>
            <div class="mt-4">
                <?php if ( isset( $import_result['error'] ) ) : ?>
                <div class="notice notice-error is-dismissible">
                    <p><?php echo esc_html( $import_result['error'] ); ?></p>
                </div>
                <?php else : ?>
                <div class="notice notice-success is-dismissible">
                    <p>
                        <?php
                        printf(
                            /* translators: %d: number of imported redirects */
                            esc_html__( 'Imported: %d', 'redirect-360' ),
                            esc_html( $import_result['imported'] )
                        );
                        ?>,
                        <?php
                        printf(
                            /* translators: %d: number of updated redirects */
                            esc_html__( 'Updated: %d', 'redirect-360' ),
                            esc_html( $import_result['updated'] )
                        );
                        ?>,
                        <?php
                        printf(
                            /* translators: %d: number of skipped redirects */
                            esc_html__( 'Skipped: %d', 'redirect-360' ),
                            esc_html( $import_result['skipped'] )
                        );
                        ?>
                    </p>
                </div>
                <?php if ( ! empty( $import_result['errors'] ) ) : ?>
                <div class="notice notice-warning is-dismissible">
                    <p>
                        <?php esc_html_e( 'Errors:', 'redirect-360' ); ?><br>
                        <?php echo implode( '<br>', array_map( 'esc_html', $import_result['errors'] ) ); ?>
                    </p>
                </div>
                <?php endif; ?>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="bg-white p-4 shadow rounded">
            <h2 class="text-lg font-bold"><?php esc_html_e( 'Export Redirect Rules', 'redirect-360' ); ?></h2>
            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                <input type="hidden" name="action" value="r360_export_redirects">
                <?php wp_nonce_field( 'r360_export_redirects' ); ?>
                <button type="submit"
                    class="bg-[#2563eb] cursor-pointer text-white px-5 py-2.5 rounded shadow hover:bg-blue-700 transition">
                    <?php esc_html_e( 'Export Redirects', 'redirect-360' ); ?>
                </button>
            </form>
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