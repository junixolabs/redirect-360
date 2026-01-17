<?php
defined( 'ABSPATH' ) || exit;

if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( 'Access denied.' );
}

$id = isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0;
$redirect = $id ? Redirect_360_Redirects::get_redirects( $id ) : array( 'from_url' => '', 'to_url' => '', 'redirect_type' => 301, 'enabled' => 1 );

// Handle form submit.
if ( isset( $_POST['submit'] ) && check_admin_referer( 'add_redirect' ) ) {
    $data = array(
        'from_url'      => $_POST['from_url'],
        'to_url'        => $_POST['to_url'],
        'redirect_type' => $_POST['redirect_type'],
        'enabled'       => isset( $_POST['enabled'] ) ? 1 : 0,
    );

    if ( $id ) {
        Redirect_360_Redirects::update_redirect( $id, $data );
        echo '<div class="notice notice-success"><p>Redirect updated.</p></div>';
    } else {
        Redirect_360_Redirects::add_redirect( $data );
        echo '<div class="notice notice-success"><p>Redirect added.</p></div>';
    }
    // Refresh redirect data.
    $redirect = $id ? Redirect_360_Redirects::get_redirects( $id ) : array( 'from_url' => '', 'to_url' => '', 'redirect_type' => 301, 'enabled' => 1 );
}
?>

<div class="wrap bg-gray-100 min-h-screen p-6">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex items-center mb-6">
                <img src="<?php echo REDIRECT_360_PLUGIN_URL . 'assets/logo.png'; ?>" alt="Logo" class="h-10 mr-4">
                <h1 class="text-3xl font-bold text-gray-900"><?php echo $id ? 'Edit' : 'Add'; ?> Redirect</h1>
            </div>
            <form method="post" class="space-y-6">
                <?php wp_nonce_field( 'add_redirect' ); ?>
                <div>
                    <label for="from_url" class="block text-sm font-medium text-gray-700">From URL (relative, e.g.,
                        /old-page)</label>
                    <input type="text" name="from_url" id="from_url"
                        value="<?php echo esc_attr( $redirect['from_url'] ); ?>"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                </div>
                <div>
                    <label for="to_url" class="block text-sm font-medium text-gray-700">To URL (absolute or
                        relative)</label>
                    <input type="text" name="to_url" id="to_url" value="<?php echo esc_attr( $redirect['to_url'] ); ?>"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required>
                </div>
                <div>
                    <label for="redirect_type" class="block text-sm font-medium text-gray-700">Redirect Type</label>
                    <select name="redirect_type" id="redirect_type"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                        <option value="301" <?php selected( $redirect['redirect_type'], 301 ); ?>>301 (Permanent)
                        </option>
                        <option value="302" <?php selected( $redirect['redirect_type'], 302 ); ?>>302 (Temporary)
                        </option>
                        <option value="307" <?php selected( $redirect['redirect_type'], 307 ); ?>>307 (Temporary)
                        </option>
                        <option value="410" <?php selected( $redirect['redirect_type'], 410 ); ?>>410 (Gone)</option>
                    </select>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="enabled" id="enabled" <?php checked( $redirect['enabled'], 1 ); ?>
                        class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                    <label for="enabled" class="ml-2 block text-sm text-gray-900">Enabled</label>
                </div>
                <button type="submit" name="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Save Redirect</button>
            </form>
        </div>
    </div>
</div>