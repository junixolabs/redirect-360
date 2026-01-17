<?php
defined( 'ABSPATH' ) || exit;

if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( 'Access denied.' );
}

// Handle upload.
if ( isset( $_POST['submit'] ) && check_admin_referer( 'import_csv' ) && isset( $_FILES['csv_file'] ) ) {
    $result = Redirect_360_Importer::import_csv( $_FILES['csv_file'] );
    if ( isset( $result['error'] ) ) {
        echo '<div class="notice notice-error"><p>' . esc_html( $result['error'] ) . '</p></div>';
    } else {
        echo '<div class="notice notice-success"><p>Imported ' . $result['imported'] . ' redirects.</p></div>';
        if ( ! empty( $result['errors'] ) ) {
            echo '<div class="notice notice-warning"><p>Errors: ' . implode( '<br>', array_map( 'esc_html', $result['errors'] ) ) . '</p></div>';
        }
    }
}
?>

<div class="wrap bg-gray-100 min-h-screen p-6">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex items-center mb-6">
                <img src="<?php echo REDIRECT_360_PLUGIN_URL . 'assets/logo.png'; ?>" alt="Logo" class="h-10 mr-4">
                <h1 class="text-3xl font-bold text-gray-900">Bulk Import</h1>
            </div>
            <p class="mb-4">Upload a CSV file with columns: from_url,to_url,redirect_type,enabled (1/0). No header
                required, but skipped if present. from_url will be normalized to relative.</p>
            <form method="post" enctype="multipart/form-data" class="space-y-6">
                <?php wp_nonce_field( 'import_csv' ); ?>
                <div>
                    <label for="csv_file" class="block text-sm font-medium text-gray-700">CSV File</label>
                    <input type="file" name="csv_file" id="csv_file"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required
                        accept=".csv">
                </div>
                <button type="submit" name="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Import</button>
            </form>
        </div>
    </div>
</div>