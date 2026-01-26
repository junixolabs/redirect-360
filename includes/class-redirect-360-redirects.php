<?php
defined( 'ABSPATH' ) || exit;

class R360_Redirects {

    public static function get_redirects( $id = null ) {
        global $wpdb;
        $table = $wpdb->prefix . 'r360_redirects';

        if ( $id ) {
            return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE id = %d", $id ), ARRAY_A );
        }

        return $wpdb->get_results( "SELECT * FROM $table ORDER BY id DESC", ARRAY_A );
    }

    public static function add_redirect( $data ) {
        global $wpdb;
        $table = $wpdb->prefix . 'r360_redirects';

        // Normalize from_url to relative path without trailing slash.
        $home = home_url();
        $from_url_raw = $data['from_url'] ?? '';
        $from_url = str_replace( $home, '', $from_url_raw );
        $from_url = preg_replace( '/\s+/', '', $from_url );  // Remove spaces
        if ( strpos( $from_url, '/' ) !== 0 ) {
            $from_url = '/' . $from_url;
        }
        $from_url = rtrim( sanitize_text_field( $from_url ), '/' );

        $to_url_raw = $data['to_url'] ?? '';
        $to_url = esc_url_raw( $to_url_raw );  // Use esc_url_raw() for URLs that will be stored/used in redirects

        $redirect_type = isset( $data['redirect_type'] ) ? absint( $data['redirect_type'] ) : 301;

        $wpdb->insert(
            $table,
            array(
                'from_url'      => $from_url,
                'to_url'        => $to_url,
                'redirect_type' => $redirect_type,
                'enabled'       => 1,
            ),
            array(
                '%s',
                '%s',
                '%d',
                '%d',
            )
        );

        return $wpdb->insert_id;
    }

    public static function update_redirect( $id, $data ) {
        global $wpdb;
        $table = $wpdb->prefix . 'r360_redirects';

        // Normalize from_url.
        $home = home_url();
        $from_url_raw = $data['from_url'] ?? '';
        $from_url = str_replace( $home, '', $from_url_raw );
        $from_url = preg_replace( '/\s+/', '', $from_url );  // Remove spaces
        if ( strpos( $from_url, '/' ) !== 0 ) {
            $from_url = '/' . $from_url;
        }
        $from_url = rtrim( sanitize_text_field( $from_url ), '/' );

        $to_url_raw = $data['to_url'] ?? '';
        $to_url = esc_url_raw( $to_url_raw );

        $redirect_type = isset( $data['redirect_type'] ) ? absint( $data['redirect_type'] ) : 301;

        $wpdb->update(
            $table,
            array(
                'from_url'      => $from_url,
                'to_url'        => $to_url,
                'redirect_type' => $redirect_type,
            ),
            array( 'id' => absint( $id ) ),
            array( '%s', '%s', '%d' ),
            array( '%d' )
        );
    }

    public static function delete_redirect( $id ) {
        global $wpdb;
        $table = $wpdb->prefix . 'r360_redirects';
        $wpdb->delete( $table, array( 'id' => absint( $id ) ), array( '%d' ) );
    }

    public static function get_hits_count( $id ) {
        global $wpdb;
        $table = $wpdb->prefix . 'r360_logs';
        return (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table WHERE redirect_id = %d", absint( $id ) ) );
    }
}