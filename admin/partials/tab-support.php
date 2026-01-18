<?php
defined( 'ABSPATH' ) || exit;

if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( 'Access denied.' );
}
?>

<div class="flex flex-wrap -mx-4">
    <div class="w-3/4 px-4">
        <div class="bg-white p-4 rounded shadow">
            <h2 class="text-lg font-bold">Support</h2>
            <p>Contact: shubhadip@junixo.com</p>
            <p>Support the project: <a href="https://buymeacoffee.com/shubhadipbhowmik" class="text-blue-500">Buy Me a
                    Coffee</a></p>
        </div>
    </div>

    <!-- support section  -->
    <!-- support section  -->
    <div class="w-1/4 px-4">
        <div class="border-2 border-blue-600 bg-white p-6 shadow-sm">

            <!-- Header -->
            <div class="text-center">
                <h2 class="text-2xl font-bold text-blue-700">
                    Redirect 360
                </h2>
                <p class="mt-2 text-sm text-slate-600">
                    Free & Open-Source WordPress Redirection Plugin
                </p>
            </div>

            <!-- Divider -->
            <div class="my-4 h-px bg-blue-100"></div>

            <!-- Features -->
            <ul class="space-y-2 text-sm text-slate-700">
                <li>• Internal & External URL Redirects</li>
                <li>• Chart-based Redirect Analytics</li>
                <li>• Bulk Import & Export (CSV)</li>
                <li>• Easy Redirect Management Dashboard</li>
                <li>• Actively Maintained Open-Source Project</li>
                <li>• Community-Driven Development</li>

            </ul>

            <!-- CTA -->
            <div class="mt-6 text-center">
                <a href="https://buymeacoffee.com/shubhadipbhowmik" target="_blank"
                    class="inline-block w-full bg-blue-600 px-4 py-3 text-sm font-semibold text-white"
                    style="color: #ffffff !important">
                    ☕ Support Development
                </a>

                <p class="mt-2 text-xs text-slate-500">
                    Optional support. No paywalls. Ever.
                </p>
            </div>

        </div>
    </div>


</div>