@extends('layouts.employer')

@section('page-title', 'Application Review')

@section('dashboard-content')
<section class="portal-two-col-grid">
    <article class="kit-card pad-lg">
        <div class="kit-card-head"><div><h2>{{ $application->alumni?->name }}</h2><p class="kit-card-subtitle">{{ $application->job?->title }}</p></div></div>
        <p class="kit-muted">{{ $application->cover_letter }}</p>
        <div class="kit-action-row"><a class="kit-button secondary" href="{{ route('employer.applications.resume', $application) }}" target="_blank">View Resume</a></div>
    </article>

    <article class="kit-card pad-lg">
        <div class="kit-card-head"><div><h2>Update Status</h2><p class="kit-card-subtitle">Notify the alumnus about the result.</p></div></div>
        <form method="POST" action="{{ route('employer.applications.update', $application) }}" class="kit-form-grid">
            @csrf
            @method('PUT')
            <div class="kit-field">
                <label for="status">Status</label>
                <select id="status" name="status">
                    @foreach (['pending', 'shortlisted', 'interviewed', 'accepted', 'rejected', 'hired'] as $status)
                        <option value="{{ $status }}" @selected($application->status === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="kit-field" style="flex-basis: 100%;"><label for="status_notes">Notes</label><textarea id="status_notes" name="status_notes" rows="5">{{ old('status_notes', $application->status_notes) }}</textarea></div>
            <div class="kit-action-row"><button class="kit-button primary" type="submit">Update application</button></div>
        </form>
    </article>
</section>
@endsection
