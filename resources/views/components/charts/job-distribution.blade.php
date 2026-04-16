@props(['id' => 'job-distribution-chart', 'series' => []])

<article class="kit-dashboard-panel">
    <div class="kit-dashboard-panel-head">
        <h3>Job Distribution</h3>
        <span>By category</span>
    </div>
    <div class="portal-chart-box">
        <canvas id="{{ $id }}" data-chart="pie" data-series='@json($series)' aria-label="Job distribution chart"></canvas>
    </div>
</article>
