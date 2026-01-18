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
                'referrer'    => wp_get_referer() ? wp_get_referer() : '',
            )
        );

        // Purge old logs.
        self::purge_old_logs();
    }

    private static function purge_old_logs() {
        global $wpdb;
        $table = $wpdb->prefix . 'redirect_360_logs';
        $settings = get_option( 'redirect_360_settings', array() );
        $days = ! empty( $settings['log_retention_days'] ) ? intval( $settings['log_retention_days'] ) : 30;
        if ( $days > 0 ) {
            $wpdb->query( $wpdb->prepare( "DELETE FROM $table WHERE hit_time < DATE_SUB(NOW(), INTERVAL %d DAY)", $days ) );
        }
    }

    public static function get_hits_over_time( $redirect_id = null ) {
        global $wpdb;
        $table = $wpdb->prefix . 'redirect_360_logs';
        $where = $redirect_id ? $wpdb->prepare( "WHERE redirect_id = %d", $redirect_id ) : '';
        return $wpdb->get_results( "SELECT DATE(hit_time) as date, COUNT(*) as hits FROM $table $where GROUP BY date ORDER BY date ASC", ARRAY_A );
    }

    public static function get_referrer_stats( $redirect_id = null ) {
        global $wpdb;
        $table = $wpdb->prefix . 'redirect_360_logs';
        $where = $redirect_id ? $wpdb->prepare( "WHERE redirect_id = %d", $redirect_id ) : '';
        return $wpdb->get_results( "SELECT referrer, COUNT(*) as count FROM $table $where GROUP BY referrer", ARRAY_A );
    }

    public static function clear_logs() {
        global $wpdb;
        $table = $wpdb->prefix . 'redirect_360_logs';
        $wpdb->query( "TRUNCATE TABLE $table" );
    }
}