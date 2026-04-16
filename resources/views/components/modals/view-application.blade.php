@props(['application'])

<div class="kit-modal" id="application-modal-{{ $application->id }}">
    <div class="kit-modal-panel">
        <div class="kit-modal-head">
            <div>
                <strong>{{ $application->alumni?->name }}</strong>
                <p class="kit-muted" style="margin: 6px 0 0;">Application for {{ $application->job?->title }}</p>
            </div>
            <button class="kit-close" type="button" data-modal-close><i class="ph ph-x"></i></button>
        </div>
        <div class="kit-modal-body">
            <div class="portal-detail-grid">
                <div><strong>Status</strong><p>{{ ucfirst($application->status) }}</p></div>
                <div><strong>Availability</strong><p>{{ optional($application->availability_date)->format('M d, Y') ?? 'Not set' }}</p></div>
                <div><strong>Expected Salary</strong><p>{{ $application->expected_salary ? 'PHP '.number_format($application->expected_salary, 2) : 'Not specified' }}</p></div>
                <div><strong>Portfolio</strong><p>{{ $application->portfolio_link ?: 'Not provided' }}</p></div>
            </div>
            <div class="kit-card pad-md" style="margin-top: 20px;">
                <strong>Cover Letter</strong>
                <p class="kit-muted" style="margin-top: 8px;">{{ $application->cover_letter }}</p>
            </div>
        </div>
    </div>
</div>
