jQuery(document).ready(function ($) {
  if (typeof redirect360Data !== "undefined") {
    // Hits Over Time Line Chart
    const hitsCtx = document
      .getElementById("hitsOverTimeChart")
      ?.getContext("2d");
    if (hitsCtx) {
      new Chart(hitsCtx, {
        type: "line",
        data: {
          labels: redirect360Data.hitsOverTime.map((item) => item.date),
          datasets: [
            {
              label: "Hits",
              data: redirect360Data.hitsOverTime.map((item) => item.hits),
              borderColor: "rgba(59, 130, 246, 1)",
              backgroundColor: "rgba(59, 130, 246, 0.2)",
              fill: true,
            },
          ],
        },
        options: {
          responsive: true,
          scales: {
            y: { beginAtZero: true },
          },
        },
      });
    }

    // Top Redirects Bar Chart
    const topCtx = document
      .getElementById("topRedirectsChart")
      ?.getContext("2d");
    if (topCtx) {
      new Chart(topCtx, {
        type: "bar",
        data: {
          labels: redirect360Data.topRedirects.map((item) => item.from_url),
          datasets: [
            {
              label: "Hits",
              data: redirect360Data.topRedirects.map((item) => item.hits),
              backgroundColor: "rgba(34, 197, 94, 0.7)",
            },
          ],
        },
        options: {
          responsive: true,
          scales: {
            y: { beginAtZero: true },
          },
        },
      });
    }

    // Redirect Types Pie Chart
    const typesCtx = document
      .getElementById("typesStatsChart")
      ?.getContext("2d");
    if (typesCtx) {
      new Chart(typesCtx, {
        type: "pie",
        data: {
          labels: redirect360Data.typesStats.map((item) => item.redirect_type),
          datasets: [
            {
              data: redirect360Data.typesStats.map((item) => item.hits),
              backgroundColor: [
                "rgba(239, 68, 68, 0.7)",
                "rgba(245, 158, 11, 0.7)",
                "rgba(16, 185, 129, 0.7)",
                "rgba(59, 130, 246, 0.7)",
              ],
            },
          ],
        },
        options: {
          responsive: true,
        },
      });
    }
  }
});
