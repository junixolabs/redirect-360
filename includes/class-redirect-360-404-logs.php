<?php
defined( 'ABSPATH' ) || exit;

class R360_404_Logs {

    public static function log_404( $requested_url ) {
        global $wpdb;
        $table = $wpdb->prefix . 'r360_404_logs';

        // $requested_url is already sanitized before being passed here (in main class),
        // but sanitize again defensively.
        $requested_url = sanitize_text_field( $requested_url );

        // Sanitize IP address
        $ip_raw = $_SERVER['REMOTE_ADDR'] ?? '';
        $ip = sanitize_text_field( wp_unslash( $ip_raw ) );

        // Referrer sanitized as URL
        $referrer = wp_get_referer() ? esc_url_raw( wp_get_referer() ) : '';

        $wpdb->insert(
            $table,
            array(
                'requested_url' => $requested_url,
                'hit_time'      => current_time( 'mysql' ),
                'ip'            => $ip,
                'referrer'      => $referrer,
            ),
            array(
                '%s',
                '%s',
                '%s',
                '%s',
            )
        );

        // Purge old logs
        $settings = get_option( 'r360_settings', array() );
        $days = ! empty( $settings['log_retention_days'] ) ? absint( $settings['log_retention_days'] ) : 30;

        if ( $days > 0 ) {
            $wpdb->query(
                $wpdb->prepare(
                    "DELETE FROM `{$table}` WHERE hit_time < DATE_SUB( NOW(), INTERVAL %d DAY )",
                    $days
                )
            );
        }
    }

    public static function get_logs() {
        global $wpdb;
        $table = $wpdb->prefix . 'r360_404_logs';

        $query = "SELECT requested_url, 
                         MAX(hit_time) as last_hit, 
                         COUNT(*) as hit_count 
                  FROM `{$table}` 
                  GROUP BY requested_url 
                  ORDER BY last_hit DESC";

        return $wpdb->get_results( $query, ARRAY_A );
    }

    public static function clear_logs() {
        global $wpdb;
        $table = $wpdb->prefix . 'r360_404_logs';
        $wpdb->query( "TRUNCATE TABLE `{$table}`" );
    }
}