<?php
defined( 'ABSPATH' ) || exit;

if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( 'Access denied.' );
}

// Handle delete.
if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete' && isset( $_GET['id'] ) && check_admin_referer( 'delete_redirect' ) ) {
    Redirect_360_Redirects::delete_redirect( intval( $_GET['id'] ) );
    echo '<div class="notice notice-success"><p>Redirect deleted.</p></div>';
}

$redirects = Redirect_360_Redirects::get_redirects();
?>

<div class="wrap bg-gray-100 min-h-screen p-6">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex items-center mb-6">
                <img src="<?php echo REDIRECT_360_PLUGIN_URL . 'assets/logo.png'; ?>" alt="Logo" class="h-10 mr-4">
                <h1 class="text-3xl font-bold text-gray-900">Redirects</h1>
            </div>
            <a href="<?php echo admin_url( 'admin.php?page=redirect-360-add' ); ?>"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Add New
                Redirect</a>
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="py-2 px-4 border-b text-left">From URL</th>
                        <th class="py-2 px-4 border-b text-left">To URL</th>
                        <th class="py-2 px-4 border-b text-left">Type</th>
                        <th class="py-2 px-4 border-b text-left">Enabled</th>
                        <th class="py-2 px-4 border-b text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ( empty( $redirects ) ) : ?>
                    <tr>
                        <td colspan="5" class="py-2 px-4 text-center">No redirects found.</td>
                    </tr>
                    <?php else : ?>
                    <?php foreach ( $redirects as $redirect ) : ?>
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4 border-b"><?php echo esc_html( $redirect['from_url'] ); ?></td>
                        <td class="py-2 px-4 border-b"><?php echo esc_html( $redirect['to_url'] ); ?></td>
                        <td class="py-2 px-4 border-b"><?php echo esc_html( $redirect['redirect_type'] ); ?></td>
                        <td class="py-2 px-4 border-b"><?php echo $redirect['enabled'] ? 'Yes' : 'No'; ?></td>
                        <td class="py-2 px-4 border-b">
                            <a href="<?php echo admin_url( 'admin.php?page=redirect-360-add&id=' . $redirect['id'] ); ?>"
                                class="text-blue-500 hover:text-blue-700">Edit</a> |
                            <a href="<?php echo wp_nonce_url( admin_url( 'admin.php?page=redirect-360&action=delete&id=' . $redirect['id'] ), 'delete_redirect' ); ?>"
                                class="text-red-500 hover:text-red-700"
                                onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>