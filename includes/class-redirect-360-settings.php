<?php
defined( 'ABSPATH' ) || exit;

class Redirect_360_Settings {

    public static function save_settings( $data ) {
        $settings = array(
            'enable_logging'     => isset( $data['enable_logging'] ) ? 1 : 0,
            'log_retention_days' => intval( $data['log_retention_days'] ),
        );

        update_option( 'redirect_360_settings', $settings );
    }

    public static function get_settings() {
        return get_option( 'redirect_360_settings', array( 'enable_logging' => 1, 'log_retention_days' => 30 ) );
    }
}