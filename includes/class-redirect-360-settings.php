<?php
defined( 'ABSPATH' ) || exit;

class R360_Settings {

    public static function save_settings( $data ) {
        // Sanitize and validate settings
        $settings = array(
            'enable_logging'     => isset( $data['enable_logging'] ) ? 1 : 0,
            'log_retention_days' => absint( $data['log_retention_days'] ?? 30 ),
        );

        // Ensure retention days is reasonable (1-365, prevent abuse)
        if ( $settings['log_retention_days'] < 1 ) {
            $settings['log_retention_days'] = 1;
        } elseif ( $settings['log_retention_days'] > 365 ) {
            $settings['log_retention_days'] = 365;
        }

        update_option( 'r360_settings', $settings );
    }

    public static function get_settings() {
        return get_option(
            'r360_settings',
            array(
                'enable_logging'     => 1,
                'log_retention_days' => 30,
            )
        );
    }
}