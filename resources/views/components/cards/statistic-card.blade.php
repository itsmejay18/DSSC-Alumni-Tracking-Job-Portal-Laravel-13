@props(['label', 'value', 'subtitle' => '', 'icon' => 'ph-chart-bar', 'tag' => null, 'tagClass' => 'kit-dashboard-tag-blue', 'statKey' => null])

<article class="kit-dashboard-stat-card">
    <div class="kit-dashboard-stat-main">
        <div>
            <p class="kit-dashboard-stat-label">{{ $label }}</p>
            <p class="kit-dashboard-stat-value" @if($statKey) data-stat-key="{{ $statKey }}" @endif>{{ $value }}</p>
            <p class="kit-dashboard-stat-subtitle">{{ $subtitle }}</p>
        </div>
        <span class="kit-dashboard-stat-icon"><i class="ph {{ $icon }}"></i></span>
    </div>
    @if ($tag)
        <div class="kit-dashboard-stat-foot">
            <span class="kit-dashboard-tag {{ $tagClass }}">{{ $tag }}</span>
        </div>
    @endif
</article>
