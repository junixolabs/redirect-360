<?php
defined( 'ABSPATH' ) || exit;

class Redirect_360_Redirects {

    public static function get_redirects( $id = null ) {
        global $wpdb;
        $table = $wpdb->prefix . 'redirect_360_redirects';

        if ( $id ) {
            return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE id = %d", $id ), ARRAY_A );
        }

        return $wpdb->get_results( "SELECT * FROM $table ORDER BY id DESC", ARRAY_A );
    }

    public static function add_redirect( $data ) {
        global $wpdb;
        $table = $wpdb->prefix . 'redirect_360_redirects';

        // Normalize from_url to relative path.
        $home = home_url();
        $from_url = str_replace( $home, '', $data['from_url'] );
        if ( ! str_starts_with( $from_url, '/' ) ) {
            $from_url = '/' . $from_url;
        }
        $from_url = sanitize_text_field( $from_url );

        // to_url can be absolute or relative.
        $to_url = sanitize_text_field( $data['to_url'] );

        $wpdb->insert(
            $table,
            array(
                'from_url'      => $from_url,
                'to_url'        => $to_url,
                'redirect_type' => intval( $data['redirect_type'] ),
                'enabled'       => isset( $data['enabled'] ) ? 1 : 0,
            )
        );

        return $wpdb->insert_id;
    }

    public static function update_redirect( $id, $data ) {
        global $wpdb;
        $table = $wpdb->prefix . 'redirect_360_redirects';

        // Normalize from_url to relative path.
        $home = home_url();
        $from_url = str_replace( $home, '', $data['from_url'] );
        if ( ! str_starts_with( $from_url, '/' ) ) {
            $from_url = '/' . $from_url;
        }
        $from_url = sanitize_text_field( $from_url );

        // to_url can be absolute or relative.
        $to_url = sanitize_text_field( $data['to_url'] );

        $wpdb->update(
            $table,
            array(
                'from_url'      => $from_url,
                'to_url'        => $to_url,
                'redirect_type' => intval( $data['redirect_type'] ),
                'enabled'       => isset( $data['enabled'] ) ? 1 : 0,
            ),
            array( 'id' => $id )
        );
    }

    public static function delete_redirect( $id ) {
        global $wpdb;
        $table = $wpdb->prefix . 'redirect_360_redirects';
        $wpdb->delete( $table, array( 'id' => $id ) );
    }
}