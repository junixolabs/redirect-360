<?php
defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

global $wpdb;

// Drop custom tables.
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}redirect_360_redirects" );
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}redirect_360_logs" );

// Delete options.
delete_option( 'redirect_360_settings' );