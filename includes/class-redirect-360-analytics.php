<?php
defined( 'ABSPATH' ) || exit;

class R360_Analytics {

    public static function log_hit( $redirect_id ) {
        global $wpdb;
        $table = $wpdb->prefix . 'r360_logs';

        // Sanitize IP address
        $ip_raw = $_SERVER['REMOTE_ADDR'] ?? '';
        $ip = sanitize_text_field( wp_unslash( $ip_raw ) );

        // Referrer is already safe from wp_get_referer(), but sanitize as URL
        $referrer = wp_get_referer() ? esc_url_raw( wp_get_referer() ) : '';

        $redirect_id = absint( $redirect_id );

        $wpdb->insert(
            $table,
            array(
                'redirect_id' => $redirect_id,
                'hit_time'    => current_time( 'mysql' ),
                'ip'          => $ip,
                'referrer'    => $referrer,
            ),
            array(
                '%d',
                '%s',
                '%s',
                '%s',
            )
        );

        // Purge old logs
        self::purge_old_logs();
    }

    private static function purge_old_logs() {
        global $wpdb;
        $table = $wpdb->prefix . 'r360_logs';

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

    public static function get_hits_over_time( $redirect_id = null ) {
        global $wpdb;
        $table = $wpdb->prefix . 'r360_logs';

        $where = '';
        if ( $redirect_id ) {
            $where = $wpdb->prepare( "WHERE redirect_id = %d", absint( $redirect_id ) );
        }

        $query = "SELECT DATE(hit_time) as date, COUNT(*) as hits 
                  FROM `{$table}` 
                  {$where} 
                  GROUP BY date 
                  ORDER BY date ASC";

        return $wpdb->get_results( $query, ARRAY_A );
    }

    public static function get_referrer_stats( $redirect_id = null ) {
        global $wpdb;
        $table = $wpdb->prefix . 'r360_logs';

        $where = '';
        if ( $redirect_id ) {
            $where = $wpdb->prepare( "WHERE redirect_id = %d", absint( $redirect_id ) );
        }

        $query = "SELECT referrer, COUNT(*) as count 
                  FROM `{$table}` 
                  {$where} 
                  GROUP BY referrer 
                  ORDER BY count DESC";

        return $wpdb->get_results( $query, ARRAY_A );
    }

    public static function clear_logs() {
        global $wpdb;
        $table = $wpdb->prefix . 'r360_logs';
        $wpdb->query( "TRUNCATE TABLE `{$table}`" );
    }
}