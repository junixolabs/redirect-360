jQuery(document).ready(function ($) {
  if (typeof r360Data !== "undefined") {
    // Hits Over Time Line Chart
    const hitsCtx = $("#hitsOverTimeChart")[0]?.getContext("2d");
    if (hitsCtx) {
      new Chart(hitsCtx, {
        type: "line",
        data: {
          labels: r360Data.hitsOverTime.map((item) => item.date),
          datasets: [
            {
              label: "Hits",
              data: r360Data.hitsOverTime.map((item) => item.hits),
              borderColor: "#2563eb",
              backgroundColor: "rgba(37, 99, 235, 0.2)",
              fill: true,
            },
          ],
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } },
      });
    }

    // Referrers Pie Chart
    const referrerCtx = $("#referrerStatsChart")[0]?.getContext("2d");
    if (referrerCtx) {
      new Chart(referrerCtx, {
        type: "pie",
        data: {
          labels: r360Data.referrerStats.map(
            (item) => item.referrer || "Direct",
          ),
          datasets: [
            {
              data: r360Data.referrerStats.map((item) => item.count),
              backgroundColor: [
                "#2563eb",
                "#60a5fa",
                "#93c5fd",
                "#bfdbfe",
                "#dbeafe",
              ],
            },
          ],
        },
        options: { responsive: true },
      });
    }
  }

  // Custom file input display for Import tab (Tools)
  // Updates the visible file name when user selects a file
  $("#csvFile").on("change", function () {
    const fileName = $(this).val().split("\\").pop() || "No file selected";
    $("#fileName").text(fileName);
  });
});
