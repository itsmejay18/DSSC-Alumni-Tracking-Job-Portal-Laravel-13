@extends('layouts.employer')

@section('page-title', 'Create Job')

@section('dashboard-content')
<section class="kit-card pad-lg">
    <form method="POST" action="{{ route('employer.jobs.store') }}" class="kit-form-grid">
        @csrf
        @include('employer.jobs.partials.form', ['job' => null])
        <div class="kit-action-row"><button class="kit-button primary" type="submit">Submit for Approval</button></div>
    </form>
</section>
@endsection
