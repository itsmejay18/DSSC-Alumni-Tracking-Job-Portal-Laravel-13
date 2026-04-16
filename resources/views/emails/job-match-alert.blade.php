<div style="font-family: Public Sans, Arial, sans-serif; color: #10233d;">
    <h2>New job recommendation</h2>
    <p>A new opportunity matched your alumni profile.</p>
    <p><strong>Job:</strong> {{ $match->job?->title }}</p>
    <p><strong>Company:</strong> {{ $match->job?->employer?->company_name }}</p>
    <p><strong>Match score:</strong> {{ $match->match_score }}%</p>
    <p><a href="{{ route('alumni.jobs.show', $match->job_id) }}">View job details</a></p>
</div>
