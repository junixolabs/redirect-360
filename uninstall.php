<?php
/**
 * Uninstall file for Redirect 360
 *
 * Removes all database tables and options created by the plugin.
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

global $wpdb;

// Drop custom tables (using the new unique prefix r360_).
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}r360_redirects" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}r360_logs" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}r360_404_logs" );

// Delete options (using the new unique prefix r360_).
delete_option( 'r360_settings' );