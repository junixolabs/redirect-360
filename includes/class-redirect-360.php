<?php
defined( 'ABSPATH' ) || exit;

class Redirect_360 {

    public function __construct() {
        $this->includes();
        $this->init_hooks();
    }

    private function includes() {
        require_once REDIRECT_360_PLUGIN_DIR . 'includes/class-redirect-360-redirects.php';
        require_once REDIRECT_360_PLUGIN_DIR . 'includes/class-redirect-360-analytics.php';
        require_once REDIRECT_360_PLUGIN_DIR . 'includes/class-redirect-360-importer.php';
        require_once REDIRECT_360_PLUGIN_DIR . 'includes/class-redirect-360-settings.php';
    }

    private function init_hooks() {
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
        add_action( 'admin_head', array( $this, 'add_tailwind_cdn' ) );
        add_action( 'template_redirect', array( $this, 'handle_redirects' ) );
    }

    public function add_tailwind_cdn() {
        echo '<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">';
    }

    public function admin_menu() {
        add_menu_page(
            'Redirect 360',
            'Redirect 360',
            'manage_options',
            'redirect-360',
            array( $this, 'redirects_page' ),
            'dashicons-image-rotate',
            80
        );

        add_submenu_page(
            'redirect-360',
            'Redirects',
            'Redirects',
            'manage_options',
            'redirect-360',
            array( $this, 'redirects_page' )
        );

        add_submenu_page(
            'redirect-360',
            'Add New',
            'Add New',
            'manage_options',
            'redirect-360-add',
            array( $this, 'add_redirect_page' )
        );

        add_submenu_page(
            'redirect-360',
            'Analytics',
            'Analytics',
            'manage_options',
            'redirect-360-analytics',
            array( $this, 'analytics_page' )
        );

        add_submenu_page(
            'redirect-360',
            'Import',
            'Import',
            'manage_options',
            'redirect-360-import',
            array( $this, 'import_page' )
        );

        add_submenu_page(
            'redirect-360',
            'Settings',
            'Settings',
            'manage_options',
            'redirect-360-settings',
            array( $this, 'settings_page' )
        );
    }

    public function enqueue_assets( $hook ) {
        if ( strpos( $hook, 'redirect-360' ) === false ) {
            return;
        }

        // Custom admin CSS.
        wp_enqueue_style( 'redirect-360-admin', REDIRECT_360_PLUGIN_URL . 'admin/css/admin.css', array(), REDIRECT_360_VERSION );

        // Chart.js.
        wp_enqueue_script( 'redirect-360-chartjs', REDIRECT_360_PLUGIN_URL . 'assets/js/chart.js', array(), '4.4.0', true );

        // Custom JS.
        wp_enqueue_script( 'redirect-360-admin-js', REDIRECT_360_PLUGIN_URL . 'assets/js/admin.js', array( 'jquery', 'redirect-360-chartjs' ), REDIRECT_360_VERSION, true );
    }

    public function redirects_page() {
        include REDIRECT_360_PLUGIN_DIR . 'admin/partials/redirects-list.php';
    }

    public function add_redirect_page() {
        include REDIRECT_360_PLUGIN_DIR . 'admin/partials/add-redirect.php';
    }

    public function analytics_page() {
        include REDIRECT_360_PLUGIN_DIR . 'admin/partials/analytics.php';
    }

    public function import_page() {
        include REDIRECT_360_PLUGIN_DIR . 'admin/partials/import.php';
    }

    public function settings_page() {
        include REDIRECT_360_PLUGIN_DIR . 'admin/partials/settings.php';
    }

    public function handle_redirects() {
        if ( is_admin() ) {
            return;
        }

        $current_url = home_url( add_query_arg( null, null ) );
        $redirects = Redirect_360_Redirects::get_redirects();

        foreach ( $redirects as $redirect ) {
            if ( $redirect['enabled'] && $current_url === home_url( $redirect['from_url'] ) ) {
                $settings = get_option( 'redirect_360_settings', array() );
                if ( ! empty( $settings['enable_logging'] ) ) {
                    Redirect_360_Analytics::log_hit( $redirect['id'] );
                }
                wp_redirect( $redirect['to_url'], (int) $redirect['redirect_type'] );
                exit;
            }
        }

        // For 404/broken recovery: If no match above and it's 404, re-check for path match (exact for now).
        if ( is_404() ) {
            $requested_path = '/' . trim( $_SERVER['REQUEST_URI'], '/' );
            foreach ( $redirects as $redirect ) {
                if ( $redirect['enabled'] && $requested_path === $redirect['from_url'] ) {
                    $settings = get_option( 'redirect_360_settings', array() );
                    if ( ! empty( $settings['enable_logging'] ) ) {
                        Redirect_360_Analytics::log_hit( $redirect['id'] );
                    }
                    wp_redirect( $redirect['to_url'], (int) $redirect['redirect_type'] );
                    exit;
                }
            }
        }
    }

    public static function activate() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        // Redirects table.
        $sql = "CREATE TABLE {$wpdb->prefix}redirect_360_redirects (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            from_url VARCHAR(255) NOT NULL,
            to_url VARCHAR(255) NOT NULL,
            redirect_type INT NOT NULL DEFAULT 301,
            enabled TINYINT(1) NOT NULL DEFAULT 1,
            PRIMARY KEY (id)
        ) $charset_collate;";
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );

        // Logs table.
        $sql = "CREATE TABLE {$wpdb->prefix}redirect_360_logs (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            redirect_id BIGINT(20) UNSIGNED NOT NULL,
            hit_time DATETIME NOT NULL,
            ip VARCHAR(45) NOT NULL,
            referrer TEXT,
            PRIMARY KEY (id)
        ) $charset_collate;";
        dbDelta( $sql );

        // Default settings.
        add_option( 'redirect_360_settings', array( 'enable_logging' => 1, 'log_retention_days' => 30 ) );
    }

    public static function deactivate() {
        // Optional cleanup.
    }
}