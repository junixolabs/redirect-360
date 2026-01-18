<?php
defined( 'ABSPATH' ) || exit;

if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( 'Access denied.' );
}

// Handle import.
$import_result = null;
if ( isset( $_POST['import_submit'] ) && check_admin_referer( 'import_csv' ) && isset( $_FILES['csv_file'] ) ) {
    $mode = isset( $_POST['duplicate_mode'] ) ? sanitize_key( $_POST['duplicate_mode'] ) : 'skip';
    $import_result = Redirect_360_Importer::import_csv( $_FILES['csv_file'], $mode );
}
?>

<div class="flex flex-wrap -mx-4">
    <div class="w-3/4 px-4">
        <div class="bg-white p-4 mb-4 rounded shadow">
            <h2 class="text-lg font-bold"> Import Redirect Rules (CSV)</h2>
            <form method="post" enctype="multipart/form-data" class="space-y-4">
                <?php wp_nonce_field( 'import_csv' ); ?>

                <!-- File Upload -->
                <div>
                    <div class="flex items-center gap-3">
                        <!-- Hidden native input -->
                        <input type="file" name="csv_file" accept=".csv" required id="csvFile" class="hidden">

                        <!-- Custom button -->
                        <label for="csvFile"
                            class="cursor-pointer bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 border border-slate-300 hover:bg-slate-200 transition">
                            Choose CSV File
                        </label>

                        <!-- File name -->
                        <span id="fileName" class="text-sm text-slate-500">
                            No file selected
                        </span>
                    </div>
                </div>

                <!-- Duplicate Mode -->
                <div class="flex gap-6 text-sm text-slate-700">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="duplicate_mode" value="skip" checked>
                        Skip Duplicates
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="duplicate_mode" value="update">
                        Update Duplicates
                    </label>
                </div>

                <!-- Helper Text -->
                <div class="bg-slate-50 border border-slate-200 p-3 text-sm">
                    <p class="mb-2 font-medium text-slate-700">
                        CSV must contain these columns (in order):
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
                    Upload CSV
                </button>
            </form>

            <?php if ( $import_result ) : ?>
            <div class="mt-4">
                <?php if ( isset( $import_result['error'] ) ) : ?>
                <div class="notice notice-error">
                    <p><?php echo esc_html( $import_result['error'] ); ?></p>
                </div>
                <?php else : ?>
                <div class="notice notice-success">
                    <p>Imported: <?php echo $import_result['imported']; ?>, Updated:
                        <?php echo $import_result['updated']; ?>, Skipped: <?php echo $import_result['skipped']; ?></p>
                </div>
                <?php if ( ! empty( $import_result['errors'] ) ) : ?>
                <div class="notice notice-warning">
                    <p>Errors: <?php echo implode( '<br>', array_map( 'esc_html', $import_result['errors'] ) ); ?></p>
                </div>
                <?php endif; ?>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="bg-white p-4 shadow">
            <h2 class="text-lg font-bold">Export Redirect Rules</h2>
            <form method="post" action="<?php echo admin_url( 'admin-post.php?action=export_redirects' ); ?>">
                <?php wp_nonce_field( 'export_redirects' ); ?>
                <button type="submit" class="bg-[#2563eb] cursor-pointer text-white p-2">Export Redirects</button>
            </form>
        </div>
    </div>

    <!-- support section  -->
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