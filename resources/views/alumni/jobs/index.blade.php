@extends('layouts.alumni')

@section('page-title', 'Available Jobs')

@section('dashboard-content')
<section class="kit-card pad-lg">
    <form method="GET" class="kit-form-grid">
        <div class="kit-field"><label for="q">Search</label><input id="q" type="text" name="q" value="{{ request('q') }}"></div>
        <div class="kit-field">
            <label for="job_category_id">Category</label>
            <select id="job_category_id" name="job_category_id">
                <option value="">All categories</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('job_category_id') == $category->id)>{{ $category->category_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="kit-action-row"><button class="kit-button primary" type="submit">Search</button></div>
    </form>
</section>

<section class="portal-card-grid">
    @forelse ($jobs as $job)
        <x-cards.job-card :job="$job" />
    @empty
        <div class="kit-empty"><i class="ph ph-briefcase"></i><h3>No jobs matched your filters.</h3></div>
    @endforelse
</section>
{{ $jobs->links() }}
@endsection
