<?php
defined( 'ABSPATH' ) || exit;

if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( 'Access denied.' );
}

$settings = Redirect_360_Settings::get_settings();

// Handle save.
if ( isset( $_POST['submit'] ) && check_admin_referer( 'save_settings' ) ) {
    Redirect_360_Settings::save_settings( $_POST );
    echo '<div class="notice notice-success"><p>Settings saved.</p></div>';
    $settings = Redirect_360_Settings::get_settings();
}

// Handle clear logs.
if ( isset( $_POST['clear_logs'] ) && check_admin_referer( 'clear_logs' ) ) {
    Redirect_360_Analytics::clear_logs();
    echo '<div class="notice notice-success"><p>Logs cleared.</p></div>';
}
?>

<div class="wrap bg-gray-100 min-h-screen p-6">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex items-center mb-6">
                <img src="<?php echo REDIRECT_360_PLUGIN_URL . 'assets/logo.png'; ?>" alt="Logo" class="h-10 mr-4">
                <h1 class="text-3xl font-bold text-gray-900">Settings</h1>
            </div>
            <form method="post" class="space-y-6">
                <?php wp_nonce_field( 'save_settings' ); ?>
                <div class="flex items-center">
                    <input type="checkbox" name="enable_logging" id="enable_logging"
                        <?php checked( $settings['enable_logging'], 1 ); ?>
                        class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                    <label for="enable_logging" class="ml-2 block text-sm text-gray-900">Enable Logging</label>
                </div>
                <div>
                    <label for="log_retention_days" class="block text-sm font-medium text-gray-700">Log Retention
                        (Days)</label>
                    <input type="number" name="log_retention_days" id="log_retention_days"
                        value="<?php echo esc_attr( $settings['log_retention_days'] ); ?>"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" min="0">
                    <p class="text-sm text-gray-500">0 for no automatic purge.</p>
                </div>
                <button type="submit" name="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Save Settings</button>
            </form>
            <form method="post" class="mt-6">
                <?php wp_nonce_field( 'clear_logs' ); ?>
                <button type="submit" name="clear_logs"
                    class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded"
                    onclick="return confirm('Clear all logs?');">Clear All Logs</button>
            </form>
        </div>
    </div>
</div>