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
        require_once REDIRECT_360_PLUGIN_DIR . 'includes/class-redirect-360-404-logs.php';
    }

    private function init_hooks() {
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
        add_action( 'admin_head', array( $this, 'redirect_360_add_tailwind_play_cdn' ) );
        add_action( 'template_redirect', array( $this, 'handle_redirects' ) );
        add_action( 'wp', array( $this, 'handle_404_logs' ) );  // Additional hook for reliable 404 detection
        add_action( 'admin_post_export_redirects', array( 'Redirect_360_Importer', 'export_csv' ) );
    }

    public function redirect_360_add_tailwind_play_cdn() {
        if ( strpos( get_current_screen()->id ?? '', 'redirect-360' ) !== false ) {
            echo '<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>';
        }
    }

    public function admin_menu() {
        add_menu_page(
            'Redirect 360',
            'Redirect 360',
            'manage_options',
            'redirect-360',
            array( $this, 'admin_page' ),
            'dashicons-image-rotate',
            80
        );
    }

    public function enqueue_assets( $hook ) {
        if ( $hook !== 'toplevel_page_redirect-360' ) {
            return;
        }

        // Custom admin CSS.
        wp_enqueue_style( 'redirect-360-admin', REDIRECT_360_PLUGIN_URL . 'admin/css/admin.css', array(), REDIRECT_360_VERSION );

        // Chart.js.
        wp_enqueue_script( 'redirect-360-chartjs', REDIRECT_360_PLUGIN_URL . 'assets/js/chart.js', array(), '4.4.0', true );

        // Custom JS.
        wp_enqueue_script( 'redirect-360-admin-js', REDIRECT_360_PLUGIN_URL . 'assets/js/admin.js', array( 'jquery', 'redirect-360-chartjs' ), REDIRECT_360_VERSION, true );
    }

    public function admin_page() {
        $tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'rules';
        ?>
<div class="redirect-360-wrap wrap">
    <h1 class="wp-heading-inline">Redirect 360</h1>
    <nav class="nav-tab-wrapper wp-clearfix">
        <a href="<?php echo admin_url( 'admin.php?page=redirect-360&tab=rules' ); ?>"
            class="nav-tab <?php echo $tab === 'rules' ? 'nav-tab-active' : ''; ?>">Redirect Rules</a>
        <a href="<?php echo admin_url( 'admin.php?page=redirect-360&tab=error_log' ); ?>"
            class="nav-tab <?php echo $tab === 'error_log' ? 'nav-tab-active' : ''; ?>">404 Error Log</a>
        <a href="<?php echo admin_url( 'admin.php?page=redirect-360&tab=tools' ); ?>"
            class="nav-tab <?php echo $tab === 'tools' ? 'nav-tab-active' : ''; ?>">Advanced Tools</a>
        <a href="<?php echo admin_url( 'admin.php?page=redirect-360&tab=support' ); ?>"
            class="nav-tab <?php echo $tab === 'support' ? 'nav-tab-active' : ''; ?>">Support</a>
    </nav>
    <div class="tab-content">
        <?php
                switch ( $tab ) {
                    case 'rules':
                        include REDIRECT_360_PLUGIN_DIR . 'admin/partials/tab-rules.php';
                        break;
                    case 'error_log':
                        include REDIRECT_360_PLUGIN_DIR . 'admin/partials/tab-error-log.php';
                        break;
                    case 'tools':
                        include REDIRECT_360_PLUGIN_DIR . 'admin/partials/tab-tools.php';
                        break;
                    case 'support':
                        include REDIRECT_360_PLUGIN_DIR . 'admin/partials/tab-support.php';
                        break;
                }
                ?>
    </div>
</div>
<?php
    }

    public function handle_redirects() {
        if ( is_admin() ) {
            return;
        }

        $current_url = home_url( add_query_arg( null, null ) );
        $requested_uri = rtrim( $_SERVER['REQUEST_URI'], '/' );  // Normalize no trailing slash
        $requested_path = rtrim( parse_url( $requested_uri, PHP_URL_PATH ), '/' ) ?: '/';
        if ( strpos( $requested_path, '/' ) !== 0 ) {
            $requested_path = '/' . $requested_path;
        }

        $redirects = Redirect_360_Redirects::get_redirects();

        $matched = false;
        foreach ( $redirects as $redirect ) {
            $from_normalized = rtrim( $redirect['from_url'], '/' );
            if ( $redirect['enabled'] && ( $current_url === home_url( $from_normalized ) || $requested_path === $from_normalized ) ) {
                $settings = get_option( 'redirect_360_settings', array() );
                if ( ! empty( $settings['enable_logging'] ) ) {
                    Redirect_360_Analytics::log_hit( $redirect['id'] );
                }
                wp_redirect( $redirect['to_url'], (int) $redirect['redirect_type'] );
                exit;
            }
        }
    }

    public function handle_404_logs() {
        if ( is_admin() || ! is_404() ) {
            return;
        }

        $settings = get_option( 'redirect_360_settings', array() );
        if ( ! empty( $settings['enable_logging'] ) ) {
            Redirect_360_404_Logs::log_404( $_SERVER['REQUEST_URI'] );
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

        // 404 Logs table.
        $sql = "CREATE TABLE {$wpdb->prefix}redirect_360_404_logs (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            requested_url TEXT NOT NULL,
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