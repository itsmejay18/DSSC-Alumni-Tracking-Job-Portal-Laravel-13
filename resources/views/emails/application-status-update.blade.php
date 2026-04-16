<div style="font-family: Public Sans, Arial, sans-serif; color: #10233d;">
    <h2>Application status updated</h2>
    <p>Your application for <strong>{{ $application->job?->title }}</strong> is now <strong>{{ ucfirst($application->status) }}</strong>.</p>
    <p>{{ $application->status_notes ?: 'Please log in to the portal for the latest details.' }}</p>
    <p><a href="{{ route('alumni.applications.index') }}">View my applications</a></p>
</div>
