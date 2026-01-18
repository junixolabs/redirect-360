<?php
defined( 'ABSPATH' ) || exit;

class Redirect_360_404_Logs {

    public static function log_404( $requested_url ) {
        global $wpdb;
        $table = $wpdb->prefix . 'redirect_360_404_logs';

        $wpdb->insert(
            $table,
            array(
                'requested_url' => sanitize_text_field( $requested_url ),
                'hit_time'      => current_time( 'mysql' ),
                'ip'            => $_SERVER['REMOTE_ADDR'],
                'referrer'      => wp_get_referer() ? wp_get_referer() : '',
            )
        );

        // Purge old logs.
        $settings = get_option( 'redirect_360_settings', array() );
        $days = ! empty( $settings['log_retention_days'] ) ? intval( $settings['log_retention_days'] ) : 30;
        if ( $days > 0 ) {
            $wpdb->query( $wpdb->prepare( "DELETE FROM $table WHERE hit_time < DATE_SUB(NOW(), INTERVAL %d DAY)", $days ) );
        }
    }

    public static function get_logs() {
        global $wpdb;
        $table = $wpdb->prefix . 'redirect_360_404_logs';
        return $wpdb->get_results( "SELECT requested_url, MAX(hit_time) as last_hit, COUNT(*) as hit_count FROM $table GROUP BY requested_url ORDER BY last_hit DESC", ARRAY_A );
    }

    public static function clear_logs() {
        global $wpdb;
        $table = $wpdb->prefix . 'redirect_360_404_logs';
        $wpdb->query( "TRUNCATE TABLE $table" );
    }
}