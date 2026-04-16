document.addEventListener("DOMContentLoaded", () => {
    if (!window.Chart) {
        return;
    }

    const chartRegistry = {};

    function renderChart(canvas) {
        const type = canvas.dataset.chart;
        const series = JSON.parse(canvas.dataset.series || "[]");
        const labels = series.map((item) => item.label);
        const values = series.map((item) => item.value);

        if (chartRegistry[canvas.id]) {
            chartRegistry[canvas.id].destroy();
        }

        chartRegistry[canvas.id] = new window.Chart(canvas, {
            type,
            data: {
                labels,
                datasets: [{
                    label: canvas.id,
                    data: values,
                    borderColor: "#1f6fbf",
                    backgroundColor: ["#1f6fbf", "#0e9f6e", "#c98a11", "#d14343", "#5f6f85", "#95c3ff"],
                    fill: type === "line",
                    tension: 0.35,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: type !== "line" },
                },
            },
        });
    }

    document.querySelectorAll("canvas[data-chart]").forEach(renderChart);

    if (window.location.pathname.endsWith("/admin/dashboard")) {
        setInterval(async () => {
            const response = await fetch("/admin/dashboard/data", {
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
            });

            if (!response.ok) {
                return;
            }

            const payload = await response.json();

            document.querySelectorAll("[data-stat-key]").forEach((element) => {
                const key = element.dataset.statKey;
                if (payload.stats[key] !== undefined) {
                    element.textContent = payload.stats[key];
                }
            });

            const trend = document.getElementById("admin-employment-trend");
            const distribution = document.getElementById("admin-job-distribution");

            if (trend) {
                trend.dataset.series = JSON.stringify(payload.employmentTrend || []);
                renderChart(trend);
            }

            if (distribution) {
                distribution.dataset.series = JSON.stringify(payload.jobDistribution || []);
                renderChart(distribution);
            }
        }, 30000);
    }
});
