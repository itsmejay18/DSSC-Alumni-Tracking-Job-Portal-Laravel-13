@props(['id' => 'employment-trend-chart', 'series' => []])

<article class="kit-dashboard-panel kit-dashboard-panel-wide">
    <div class="kit-dashboard-panel-head">
        <h3>Employment Trend</h3>
        <span>Last 12 months</span>
    </div>
    <div class="kit-dashboard-line-chart-wrap">
        <canvas id="{{ $id }}" data-chart="line" data-series='@json($series)' aria-label="Employment trend chart"></canvas>
    </div>
</article>
