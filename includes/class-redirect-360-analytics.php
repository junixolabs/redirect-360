<?php
defined( 'ABSPATH' ) || exit;

class Redirect_360_Analytics {

    public static function log_hit( $redirect_id ) {
        global $wpdb;
        $table = $wpdb->prefix . 'redirect_360_logs';

        $wpdb->insert(
            $table,
            array(
                'redirect_id' => $redirect_id,
                'hit_time'    => current_time( 'mysql' ),
                'ip'          => $_SERVER['REMOTE_ADDR'],
                'referrer'    => wp_get_referer(),
            )
        );

        // Purge old logs based on settings.
        $settings = get_option( 'redirect_360_settings', array() );
        $days = ! empty( $settings['log_retention_days'] ) ? intval( $settings['log_retention_days'] ) : 30;
        if ( $days > 0 ) {
            $wpdb->query( $wpdb->prepare( "DELETE FROM $table WHERE hit_time < DATE_SUB(NOW(), INTERVAL %d DAY)", $days ) );
        }
    }

    public static function get_logs( $redirect_id = null ) {
        global $wpdb;
        $table = $wpdb->prefix . 'redirect_360_logs';

        $where = $redirect_id ? $wpdb->prepare( "WHERE redirect_id = %d", $redirect_id ) : '';

        return $wpdb->get_results( "SELECT * FROM $table $where ORDER BY hit_time DESC", ARRAY_A );
    }

    public static function get_hits_over_time() {
        global $wpdb;
        $table = $wpdb->prefix . 'redirect_360_logs';
        return $wpdb->get_results( "SELECT DATE(hit_time) as date, COUNT(*) as hits FROM $table GROUP BY date ORDER BY date ASC", ARRAY_A );
    }

    public static function get_top_redirects() {
        global $wpdb;
        $table_logs = $wpdb->prefix . 'redirect_360_logs';
        $table_redirects = $wpdb->prefix . 'redirect_360_redirects';
        return $wpdb->get_results( "SELECT r.from_url, COUNT(l.id) as hits FROM $table_logs l JOIN $table_redirects r ON l.redirect_id = r.id GROUP BY l.redirect_id ORDER BY hits DESC LIMIT 10", ARRAY_A );
    }

    public static function get_redirect_types_stats() {
        global $wpdb;
        $table_logs = $wpdb->prefix . 'redirect_360_logs';
        $table_redirects = $wpdb->prefix . 'redirect_360_redirects';
        return $wpdb->get_results( "SELECT r.redirect_type, COUNT(l.id) as hits FROM $table_logs l JOIN $table_redirects r ON l.redirect_id = r.id GROUP BY r.redirect_type", ARRAY_A );
    }

    public static function clear_logs() {
        global $wpdb;
        $table = $wpdb->prefix . 'redirect_360_logs';
        $wpdb->query( "TRUNCATE TABLE $table" );
    }
}