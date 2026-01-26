<?php
defined( 'ABSPATH' ) || exit;

class R360_Importer {

    public static function import_csv( $file, $mode ) {
        if ( ! current_user_can( 'manage_options' ) || ! isset( $file['tmp_name'] ) ) {
            return array( 'error' => 'Invalid upload.' );
        }

        $handle = fopen( $file['tmp_name'], 'r' );
        if ( ! $handle ) {
            return array( 'error' => 'Could not open file.' );
        }

        $imported = 0;
        $updated  = 0;
        $skipped  = 0;
        $errors   = array();

        // Expect header: status_code,from_url,redirect_url
        $header = fgetcsv( $handle );
        $expected_header = array( 'status_code', 'from_url', 'redirect_url' );
        if ( $header && $header !== $expected_header ) {
            $errors[] = 'Invalid CSV header. Expected: status_code,from_url,redirect_url';
            fclose( $handle );
            return array(
                'imported' => $imported,
                'updated'  => $updated,
                'skipped'  => $skipped,
                'errors'   => $errors,
            );
        } elseif ( ! $header ) {
            rewind( $handle ); // No header, assume data starts immediately.
        }

        while ( ( $row = fgetcsv( $handle ) ) !== false ) {
            if ( count( $row ) !== 3 ) {
                $skipped++;
                $errors[] = 'Invalid row (wrong column count): ' . implode( ',', $row );
                continue;
            }

            $redirect_type = absint( trim( $row[0] ) );
            $from_url_raw  = trim( $row[1] );
            $to_url_raw    = trim( $row[2] );

            if (
                ! in_array( $redirect_type, array( 301, 302, 307, 410 ), true ) ||
                empty( $from_url_raw ) ||
                empty( $to_url_raw )
            ) {
                $skipped++;
                $errors[] = 'Invalid data in row: ' . implode( ',', $row );
                continue;
            }

            $data = array(
                'redirect_type' => $redirect_type,
                'from_url'      => $from_url_raw,
                'to_url'        => $to_url_raw,
                'enabled'       => 1,
            );

            // Check if exists by normalized from_url
            global $wpdb;
            $table           = $wpdb->prefix . 'r360_redirects';
            $normalized_from = self::normalize_from_url( $data['from_url'] );

            $existing = $wpdb->get_var(
                $wpdb->prepare( "SELECT id FROM `{$table}` WHERE from_url = %s", $normalized_from )
            );

            if ( $existing ) {
                if ( $mode === 'skip' ) {
                    $skipped++;
                    continue;
                } elseif ( $mode === 'update' ) {
                    R360_Redirects::update_redirect( absint( $existing ), $data );
                    $updated++;
                    continue;
                }
            }

            // add_redirect() will handle final sanitization of URLs
            R360_Redirects::add_redirect( $data );
            $imported++;
        }

        fclose( $handle );

        return array(
            'imported' => $imported,
            'updated'  => $updated,
            'skipped'  => $skipped,
            'errors'   => $errors,
        );
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
        if ( ! current_user_can( 'manage_options' ) || ! check_admin_referer( 'r360_export_redirects' ) ) {
            wp_die( 'Access denied.' );
        }

        $redirects = R360_Redirects::get_redirects();

        $site_name = sanitize_title( get_bloginfo( 'name' ) );
        $filename  = 'r360_redirects_' . date( 'd_m_Y' ) . '_' . $site_name . '.csv';

        header( 'Content-Type: text/csv; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename="' . $filename . '"' );

        $output = fopen( 'php://output', 'w' );
        fputcsv( $output, array( 'status_code', 'from_url', 'redirect_url' ) );

        foreach ( $redirects as $redirect ) {
            // Output full from_url (with home_url) as is common for exports
            fputcsv(
                $output,
                array(
                    $redirect['redirect_type'],
                    home_url( $redirect['from_url'] ),
                    $redirect['to_url'],
                )
            );
        }

        fclose( $output );
        exit;
    }
}