<?php
/**
 * Plugin Name: Redirect 360
 * Plugin URI:  https://redirect-360.vercel.app/
 * Description: Redirect 360 is a lightweight WordPress redirection plugin that silently recovers lost traffic and protects SEO from broken, expired, and mistyped URLs.
 * Version:     1.0.0
 * Author:      Shubhadip Bhowmik
 * Author URI:  https://shubhadipbhowmik.vercel.app/
 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: redirect-360
 */

defined( 'ABSPATH' ) || exit;

// Define constants.
define( 'REDIRECT_360_VERSION', '1.0.0' );
define( 'REDIRECT_360_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'REDIRECT_360_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include main class.
require_once REDIRECT_360_PLUGIN_DIR . 'includes/class-redirect-360.php';

// Initialize the plugin.
function redirect_360_init() {
    new Redirect_360();
}
add_action( 'plugins_loaded', 'redirect_360_init' );

// Activation hook.
register_activation_hook( __FILE__, array( 'Redirect_360', 'activate' ) );

// Deactivation hook.
register_deactivation_hook( __FILE__, array( 'Redirect_360', 'deactivate' ) );