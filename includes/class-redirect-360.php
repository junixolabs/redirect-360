<?php
defined( 'ABSPATH' ) || exit;

class R360 {

    public function __construct() {
        $this->includes();
        $this->init_hooks();
    }

    private function includes() {
        require_once R360_PLUGIN_DIR . 'includes/class-redirect-360-redirects.php';
        require_once R360_PLUGIN_DIR . 'includes/class-redirect-360-analytics.php';
        require_once R360_PLUGIN_DIR . 'includes/class-redirect-360-importer.php';
        require_once R360_PLUGIN_DIR . 'includes/class-redirect-360-settings.php';
        require_once R360_PLUGIN_DIR . 'includes/class-redirect-360-404-logs.php';
    }

    private function init_hooks() {
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_tailwind' ) );
        add_action( 'template_redirect', array( $this, 'handle_redirects' ) );
        add_action( 'wp', array( $this, 'handle_404_logs' ) );
        add_action( 'admin_post_r360_export_redirects', array( 'R360_Importer', 'export_csv' ) );
    }

    public function enqueue_tailwind() {
        $screen = get_current_screen();
        if ( $screen && strpos( $screen->id ?? '', 'r360' ) !== false ) {
            wp_enqueue_style(
                'r360-tailwind',
                R360_PLUGIN_URL . 'assets/css/tailwind.min.css',
                array(),
                R360_VERSION
            );
        }
    }

    public function admin_menu() {
        add_menu_page(
            'Redirect 360',
            'Redirect 360',
            'manage_options',
            'r360',
            array( $this, 'admin_page' ),
            'dashicons-randomize',
            65
        );
    }

    public function enqueue_assets( $hook ) {
        if ( $hook !== 'toplevel_page_r360' ) {
            return;
        }

        wp_enqueue_style( 'r360-admin', R360_PLUGIN_URL . 'admin/css/admin.css', array(), R360_VERSION );

        wp_enqueue_script( 'r360-chartjs', R360_PLUGIN_URL . 'assets/js/chart.js', array(), '4.4.0', true );

        wp_enqueue_script( 'r360-admin-js', R360_PLUGIN_URL . 'assets/js/admin.js', array( 'jquery', 'r360-chartjs' ), R360_VERSION, true );
    }

    public function admin_page() {
        $tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'rules';
        ?>
<div class="r360-wrap wrap">
    <h1 class="wp-heading-inline"><?php esc_html_e( 'Redirect 360', 'redirect-360' ); ?></h1>
    <nav class="nav-tab-wrapper wp-clearfix">
        <a href="<?php echo esc_url( admin_url( 'admin.php?page=r360&tab=rules' ) ); ?>"
            style="margin-left: 0px !important;"
            class="nav-tab <?php echo esc_attr( $tab === 'rules' ? 'nav-tab-active' : '' ); ?>"><?php esc_html_e( 'Redirect Rules', 'redirect-360' ); ?></a>
        <a href="<?php echo esc_url( admin_url( 'admin.php?page=r360&tab=error_log' ) ); ?>"
            class="nav-tab <?php echo esc_attr( $tab === 'error_log' ? 'nav-tab-active' : '' ); ?>"><?php esc_html_e( '404 Error Log', 'redirect-360' ); ?></a>
        <a href="<?php echo esc_url( admin_url( 'admin.php?page=r360&tab=tools' ) ); ?>"
            class="nav-tab <?php echo esc_attr( $tab === 'tools' ? 'nav-tab-active' : '' ); ?>"><?php esc_html_e( 'Advanced Tools', 'redirect-360' ); ?></a>
        <a href="<?php echo esc_url( admin_url( 'admin.php?page=r360&tab=support' ) ); ?>"
            class="nav-tab <?php echo esc_attr( $tab === 'support' ? 'nav-tab-active' : '' ); ?>"><?php esc_html_e( 'Support', 'redirect-360' ); ?></a>
    </nav>
    <div class="tab-content">
        <?php
        switch ( $tab ) {
            case 'rules':
                include R360_PLUGIN_DIR . 'admin/partials/tab-rules.php';
                break;
            case 'error_log':
                include R360_PLUGIN_DIR . 'admin/partials/tab-error-log.php';
                break;
            case 'tools':
                include R360_PLUGIN_DIR . 'admin/partials/tab-tools.php';
                break;
            case 'support':
                include R360_PLUGIN_DIR . 'admin/partials/tab-support.php';
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

        // Sanitize REQUEST_URI early
        $requested_uri_raw = $_SERVER['REQUEST_URI'] ?? '';
        $requested_uri = rtrim( sanitize_text_field( wp_unslash( $requested_uri_raw ) ), '/' );

        $requested_path = parse_url( $requested_uri, PHP_URL_PATH );
        $requested_path = $requested_path ? rtrim( $requested_path, '/' ) : '/';
        if ( strpos( $requested_path, '/' ) !== 0 ) {
            $requested_path = '/' . $requested_path;
        }

        $redirects = R360_Redirects::get_redirects();

        foreach ( $redirects as $redirect ) {
            $from_normalized = rtrim( $redirect['from_url'], '/' );
            if ( $redirect['enabled'] && ( $current_url === home_url( $from_normalized ) || $requested_path === $from_normalized ) ) {
                $settings = get_option( 'r360_settings', array() );
                if ( ! empty( $settings['enable_logging'] ) ) {
                    R360_Analytics::log_hit( $redirect['id'] );
                }
                wp_redirect( esc_url_raw( $redirect['to_url'] ), (int) $redirect['redirect_type'] );
                exit;
            }
        }
    }

    public function handle_404_logs() {
        if ( is_admin() || ! is_404() ) {
            return;
        }

        $settings = get_option( 'r360_settings', array() );
        if ( ! empty( $settings['enable_logging'] ) ) {
            // Sanitize before passing to logging method
            $requested_uri_raw = $_SERVER['REQUEST_URI'] ?? '';
            $requested_url = sanitize_text_field( wp_unslash( $requested_uri_raw ) );
            R360_404_Logs::log_404( $requested_url );
        }
    }

    public static function activate() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        // Redirects table
        $sql = "CREATE TABLE {$wpdb->prefix}r360_redirects (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            from_url VARCHAR(255) NOT NULL,
            to_url VARCHAR(255) NOT NULL,
            redirect_type INT NOT NULL DEFAULT 301,
            enabled TINYINT(1) NOT NULL DEFAULT 1,
            PRIMARY KEY (id)
        ) $charset_collate;";
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );

        // Logs table
        $sql = "CREATE TABLE {$wpdb->prefix}r360_logs (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            redirect_id BIGINT(20) UNSIGNED NOT NULL,
            hit_time DATETIME NOT NULL,
            ip VARCHAR(45) NOT NULL,
            referrer TEXT,
            PRIMARY KEY (id)
        ) $charset_collate;";
        dbDelta( $sql );

        // 404 Logs table
        $sql = "CREATE TABLE {$wpdb->prefix}r360_404_logs (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            requested_url TEXT NOT NULL,
            hit_time DATETIME NOT NULL,
            ip VARCHAR(45) NOT NULL,
            referrer TEXT,
            PRIMARY KEY (id)
        ) $charset_collate;";
        dbDelta( $sql );

        // Default settings
        add_option( 'r360_settings', array( 'enable_logging' => 1, 'log_retention_days' => 30 ) );
    }

    public static function deactivate() {
        // Optional cleanup on deactivation
    }
}