<?php
defined( 'ABSPATH' ) || exit;

if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( 'Access denied.' );
}
?>

<div class="flex flex-wrap -mx-4">

    <div class="w-3/4 px-4">
        <div class="bg-white p-6 shadow">

            <!-- Header -->
            <h2 class="text-xl font-semibold text-slate-800 mb-1">
                Support & Maintainer
            </h2>
            <p class="text-sm text-slate-500 mb-6">
                Redirect 360 is actively maintained and community-driven.
            </p>

            <!-- Grid -->
            <div class="grid grid-cols-2 gap-6">

                <!-- Contact -->
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="dashicons dashicons-email-alt"></span>
                        <h3 class="text-sm font-medium text-slate-700">Contact</h3>
                    </div>

                    <a href="mailto:shubhadipbhowmikdev@gmail.com" class="text-sm text-blue-600 hover:underline">
                        shubhadipbhowmikdev@gmail.com
                    </a>
                </div>

                <!-- Links -->
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="dashicons dashicons-admin-links"></span>
                        <h3 class="text-sm font-medium text-slate-700">Links</h3>
                    </div>

                    <ul class="space-y-1 text-sm">
                        <li>
                            <a href="https://shubhadipbhowmik.vercel.app/" target="_blank"
                                class="text-blue-600 hover:underline">
                                Personal Website
                            </a>
                        </li>
                        <li>
                            <a href="https://github.com/subhadipbhowmik" target="_blank"
                                class="text-blue-600 hover:underline">
                                GitHub Profile
                            </a>
                        </li>
                        <li>
                            <a href="https://www.linkedin.com/in/shubhadip-bhowmik/" target="_blank"
                                class="text-blue-600 hover:underline">
                                LinkedIn Profile
                            </a>
                        </li>
                        <li>
                            <a href="https://x.com/myselfshubhadip" target="_blank"
                                class="text-blue-600 hover:underline">
                                X (Twitter)
                            </a>
                        </li>
                    </ul>
                </div>

            </div>

            <!-- Support -->
            <div class="mt-6 border-t pt-4 flex items-center justify-between">
                <div class="flex items-center gap-2 text-sm text-slate-600">
                    <span class="dashicons dashicons-heart"></span>
                    Support ongoing development
                </div>

                <a href="https://buymeacoffee.com/shubhadipbhowmik" target="_blank" style="color: #ffffff !important"
                    class="bg-[#2563eb] px-4 py-2 text-sm cursor-pointer font-medium text-white shadow">
                    Buy Me a Coffee
                </a>
            </div>

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