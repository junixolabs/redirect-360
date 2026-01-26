<?php
/**
 * Plugin Name: Redirect 360
 * Plugin URI:  https://redirect-360.vercel.app/
 * Description: Redirect 360 is a fast and lightweight WordPress redirect manager that fixes 404 errors, manages 301 and 302 redirects, and helps recover lost SEO traffic without slowing down your site.
 * Version:     1.0.3
 * Requires at least: 6.0
 * Tested up to: 6.7
 * Requires PHP: 7.4
 * Author: Shubhadip Bhowmik
 * Author URI: https://shubhadipbhowmik.vercel.app/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: redirect-360
 */

defined( 'ABSPATH' ) || exit;

/**
 * Unique prefix chosen: r360_
 * - Avoids the common word "redirect"
 * - Short, distinct, and clearly tied to "Redirect 360"
 * - Used as r360_ for functions, options, hooks, script handles, menu slugs, etc.
 * - Used as R360_ for constants and class names (e.g., class R360, class R360_Settings)
 *
 * All subsequent files you provide will be updated to use this same prefix consistently.
 */

// Define constants (unique prefix, no collision risk).
define( 'R360_VERSION', '1.0.3' );
define( 'R360_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'R360_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include main class (file name kept for now, class name inside will be changed to R360 when you provide that file).
require_once R360_PLUGIN_DIR . 'includes/class-redirect-360.php';

// Initialize the plugin.
function r360_init() {
	new R360();
}
add_action( 'plugins_loaded', 'r360_init' );

// Activation hook.
register_activation_hook( __FILE__, array( 'R360', 'activate' ) );

// Deactivation hook.
register_deactivation_hook( __FILE__, array( 'R360', 'deactivate' ) );