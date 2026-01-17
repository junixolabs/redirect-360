<?php
defined( 'ABSPATH' ) || exit;

if ( ! current_user_can( 'manage_options' ) ) {
    wp_die( 'Access denied.' );
}

$hits_over_time = Redirect_360_Analytics::get_hits_over_time();
$top_redirects = Redirect_360_Analytics::get_top_redirects();
$types_stats = Redirect_360_Analytics::get_redirect_types_stats();

// Prepare data for JS charts.
wp_localize_script( 'redirect-360-admin-js', 'redirect360Data', array(
    'hitsOverTime' => $hits_over_time,
    'topRedirects' => $top_redirects,
    'typesStats'   => $types_stats,
) );
?>

<div class="wrap bg-gray-100 min-h-screen p-6">
    <div class="max-w-7xl mx-auto">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex items-center mb-6">
                <img src="<?php echo REDIRECT_360_PLUGIN_URL . 'assets/logo.png'; ?>" alt="Logo" class="h-10 mr-4">
                <h1 class="text-3xl font-bold text-gray-900">Analytics</h1>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-4 rounded-lg shadow">
                    <h2 class="text-xl font-semibold mb-4">Hits Over Time (Line Chart)</h2>
                    <canvas id="hitsOverTimeChart" class="w-full h-64"></canvas>
                </div>
                <div class="bg-white p-4 rounded-lg shadow">
                    <h2 class="text-xl font-semibold mb-4">Top Redirects (Bar Chart)</h2>
                    <canvas id="topRedirectsChart" class="w-full h-64"></canvas>
                </div>
                <div class="bg-white p-4 rounded-lg shadow md:col-span-2">
                    <h2 class="text-xl font-semibold mb-4">Redirect Types (Pie Chart)</h2>
                    <canvas id="typesStatsChart" class="w-full h-64"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>