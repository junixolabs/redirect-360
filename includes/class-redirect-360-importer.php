<?php
defined( 'ABSPATH' ) || exit;

class Redirect_360_Importer {

    public static function import_csv( $file ) {
        if ( ! current_user_can( 'manage_options' ) || ! isset( $file['tmp_name'] ) ) {
            return array( 'error' => 'Invalid upload.' );
        }

        $handle = fopen( $file['tmp_name'], 'r' );
        if ( ! $handle ) {
            return array( 'error' => 'Could not open file.' );
        }

        $imported = 0;
        $errors = array();

        // Skip header if present.
        fgetcsv( $handle );

        while ( ( $row = fgetcsv( $handle ) ) !== false ) {
            if ( count( $row ) < 4 ) {
                $errors[] = 'Invalid row: ' . implode( ',', $row );
                continue;
            }

            $data = array(
                'from_url'      => trim( $row[0] ),
                'to_url'        => trim( $row[1] ),
                'redirect_type' => intval( $row[2] ),
                'enabled'       => intval( $row[3] ),
            );

            if ( empty( $data['from_url'] ) || empty( $data['to_url'] ) || ! in_array( $data['redirect_type'], array( 301, 302, 307, 410 ), true ) ) {
                $errors[] = 'Invalid data in row: ' . implode( ',', $row );
                continue;
            }

            Redirect_360_Redirects::add_redirect( $data );
            $imported++;
        }

        fclose( $handle );

        return array( 'imported' => $imported, 'errors' => $errors );
    }
}