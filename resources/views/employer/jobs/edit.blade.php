@extends('layouts.employer')

@section('page-title', 'Edit Job')

@section('dashboard-content')
<section class="kit-card pad-lg">
    <form method="POST" action="{{ route('employer.jobs.update', $job) }}" class="kit-form-grid">
        @csrf
        @method('PUT')
        @include('employer.jobs.partials.form', ['job' => $job])
        <div class="kit-action-row"><button class="kit-button primary" type="submit">Update Job</button></div>
    </form>
</section>
@endsection
