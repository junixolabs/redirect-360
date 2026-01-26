<?php
defined( 'ABSPATH' ) || exit;

if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( esc_html__( 'Access denied.', 'redirect-360' ) );
}
?>

<div class="flex flex-wrap -mx-4">
    <div class="w-3/4 px-4">
        <div class="bg-white p-6 shadow rounded">

            <!-- Header -->
            <h2 class="text-xl font-semibold text-slate-800 mb-1">
                <?php esc_html_e( 'Support & Maintainer', 'redirect-360' ); ?>
            </h2>
            <p class="text-sm text-slate-500 mb-6">
                <?php esc_html_e( 'Redirect 360 is actively maintained and community-driven.', 'redirect-360' ); ?>
            </p>

            <!-- Grid -->
            <div class="grid grid-cols-2 gap-6">

                <!-- Contact -->
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="dashicons dashicons-email-alt"></span>
                        <h3 class="text-sm font-medium text-slate-700"><?php esc_html_e( 'Contact', 'redirect-360' ); ?>
                        </h3>
                    </div>

                    <a href="<?php echo esc_url( 'mailto:shubhadipbhowmikdev@gmail.com' ); ?>"
                        class="text-sm text-blue-600 hover:underline">
                        shubhadipbhowmikdev@gmail.com
                    </a>
                </div>

                <!-- Links -->
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="dashicons dashicons-admin-links"></span>
                        <h3 class="text-sm font-medium text-slate-700"><?php esc_html_e( 'Links', 'redirect-360' ); ?>
                        </h3>
                    </div>

                    <ul class="space-y-1 text-sm">
                        <li>
                            <a href="<?php echo esc_url( 'https://shubhadipbhowmik.vercel.app/' ); ?>" target="_blank"
                                rel="noopener" class="text-blue-600 hover:underline">
                                <?php esc_html_e( 'Personal Website', 'redirect-360' ); ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url( 'https://github.com/subhadipbhowmik' ); ?>" target="_blank"
                                rel="noopener" class="text-blue-600 hover:underline">
                                <?php esc_html_e( 'GitHub Profile', 'redirect-360' ); ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url( 'https://www.linkedin.com/in/shubhadip-bhowmik/' ); ?>"
                                target="_blank" rel="noopener" class="text-blue-600 hover:underline">
                                <?php esc_html_e( 'LinkedIn Profile', 'redirect-360' ); ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url( 'https://x.com/myselfshubhadip' ); ?>" target="_blank"
                                rel="noopener" class="text-blue-600 hover:underline">
                                <?php esc_html_e( 'X (Twitter)', 'redirect-360' ); ?>
                            </a>
                        </li>
                    </ul>
                </div>

            </div>

            <!-- Support -->
            <div class="mt-6 border-t pt-4 flex items-center justify-between">
                <div class="flex items-center gap-2 text-sm text-slate-600">
                    <span class="dashicons dashicons-heart"></span>
                    <?php esc_html_e( 'Support ongoing development', 'redirect-360' ); ?>
                </div>

                <a href="<?php echo esc_url( 'https://buymeacoffee.com/shubhadipbhowmik' ); ?>" target="_blank"
                    rel="noopener" class="bg-[#2563eb] px-4 py-2 text-sm cursor-pointer font-medium text-white shadow"
                    style="color: #ffffff !important">
                    <?php esc_html_e( 'Buy Me a Coffee', 'redirect-360' ); ?>
                </a>
            </div>

        </div>
    </div>

    <!-- Support sidebar -->
    <div class="w-1/4 px-4">
        <div class="border-2 border-blue-600 bg-white p-6 shadow-sm rounded">
            <!-- Header -->
            <div class="text-center">
                <h2 class="text-2xl font-bold text-blue-700">
                    <?php esc_html_e( 'Redirect 360', 'redirect-360' ); ?>
                </h2>
                <p class="mt-2 text-sm text-slate-600">
                    <?php esc_html_e( 'Free & Open-Source WordPress Redirection Plugin', 'redirect-360' ); ?>
                </p>
            </div>

            <!-- Divider -->
            <div class="my-4 h-px bg-blue-100"></div>

            <!-- Features -->
            <ul class="space-y-2 text-sm text-slate-700">
                <li>• <?php esc_html_e( 'Internal & External URL Redirects', 'redirect-360' ); ?></li>
                <li>• <?php esc_html_e( 'Chart-based Redirect Analytics', 'redirect-360' ); ?></li>
                <li>• <?php esc_html_e( 'Bulk Import & Export (CSV)', 'redirect-360' ); ?></li>
                <li>• <?php esc_html_e( 'Easy Redirect Management Dashboard', 'redirect-360' ); ?></li>
                <li>• <?php esc_html_e( 'Actively Maintained Open-Source Project', 'redirect-360' ); ?></li>
                <li>• <?php esc_html_e( 'Community-Driven Development', 'redirect-360' ); ?></li>
            </ul>

            <!-- CTA -->
            <div class="mt-6 text-center">
                <a href="<?php echo esc_url( 'https://buymeacoffee.com/shubhadipbhowmik' ); ?>" target="_blank"
                    rel="noopener" class="inline-block w-full bg-blue-600 px-4 py-3 text-sm font-semibold text-white"
                    style="color: #ffffff !important">
                    ☕ <?php esc_html_e( 'Support Development', 'redirect-360' ); ?>
                </a>

                <p class="mt-2 text-xs text-slate-500">
                    <?php esc_html_e( 'Optional support. No paywalls. Ever.', 'redirect-360' ); ?>
                </p>
            </div>
        </div>
    </div>
</div>