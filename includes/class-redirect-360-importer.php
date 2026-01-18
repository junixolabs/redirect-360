<?php
defined( 'ABSPATH' ) || exit;

class Redirect_360_Importer {

    public static function import_csv( $file, $mode ) {
        if ( ! current_user_can( 'manage_options' ) || ! isset( $file['tmp_name'] ) ) {
            return array( 'error' => 'Invalid upload.' );
        }

        $handle = fopen( $file['tmp_name'], 'r' );
        if ( ! $handle ) {
            return array( 'error' => 'Could not open file.' );
        }

        $imported = 0;
        $updated = 0;
        $skipped = 0;
        $errors = array();

        // Expect header: status_code,from_url,redirect_url
        $header = fgetcsv( $handle );
        $expected_header = array( 'status_code', 'from_url', 'redirect_url' );
        if ( $header && $header !== $expected_header ) {
            $errors[] = 'Invalid CSV header. Expected: status_code,from_url,redirect_url';
            fclose( $handle );
            return array( 'imported' => $imported, 'updated' => $updated, 'skipped' => $skipped, 'errors' => $errors );
        } elseif ( ! $header ) {
            rewind( $handle );  // No header, assume data starts.
        }

        while ( ( $row = fgetcsv( $handle ) ) !== false ) {
            if ( count( $row ) !== 3 ) {
                $errors[] = 'Invalid row: ' . implode( ',', $row );
                continue;
            }

            $data = array(
                'redirect_type' => intval( trim( $row[0] ) ),
                'from_url'      => trim( $row[1] ),
                'to_url'        => trim( $row[2] ),
                'enabled'       => 1,
            );

            if ( ! in_array( $data['redirect_type'], array( 301, 302, 307, 410 ), true ) || empty( $data['from_url'] ) || empty( $data['to_url'] ) ) {
                $errors[] = 'Invalid data in row: ' . implode( ',', $row );
                continue;
            }

            // Check if exists by normalized from_url.
            global $wpdb;
            $table = $wpdb->prefix . 'redirect_360_redirects';
            $normalized_from = self::normalize_from_url( $data['from_url'] );
            $existing = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $table WHERE from_url = %s", $normalized_from ) );

            if ( $existing ) {
                if ( $mode === 'skip' ) {
                    $skipped++;
                    continue;
                } elseif ( $mode === 'update' ) {
                    Redirect_360_Redirects::update_redirect( $existing, $data );
                    $updated++;
                    continue;
                }
            }

            Redirect_360_Redirects::add_redirect( $data );
            $imported++;
        }

        fclose( $handle );

        return array( 'imported' => $imported, 'updated' => $updated, 'skipped' => $skipped, 'errors' => $errors );
    }

    private static function normalize_from_url( $url ) {
        $home = home_url();
        $from_url = str_replace( $home, '', $url );
        $from_url = preg_replace( '/\s+/', '', $from_url );
        if ( strpos( $from_url, '/' ) !== 0 ) {
            $from_url = '/' . $from_url;
        }
        return rtrim( sanitize_text_field( $from_url ), '/' );
    }

    public static function export_csv() {
        if ( ! current_user_can( 'manage_options' ) || ! check_admin_referer( 'export_redirects' ) ) {
            wp_die( 'Access denied.' );
        }

        $redirects = Redirect_360_Redirects::get_redirects();

        $site_name = sanitize_title( get_bloginfo( 'name' ) );
        $filename = 'redirect_360_' . date( 'd_m_y' ) . '_' . $site_name . '.csv';

        header( 'Content-Type: text/csv' );
        header( 'Content-Disposition: attachment; filename="' . $filename . '"' );

        $output = fopen( 'php://output', 'w' );
        fputcsv( $output, array( 'status_code', 'from_url', 'redirect_url' ) );

        foreach ( $redirects as $redirect ) {
            fputcsv( $output, array( $redirect['redirect_type'], home_url( $redirect['from_url'] ), $redirect['to_url'] ) );
        }

        fclose( $output );
        exit;
    }
}