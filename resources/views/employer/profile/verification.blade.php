@extends('layouts.employer')

@section('page-title', 'Verification Status')

@section('dashboard-content')
<section class="kit-card pad-lg">
    <div class="kit-card-head"><div><h2>Employer Verification</h2><p class="kit-card-subtitle">Admin approval is required before you can publish jobs.</p></div></div>
    <div class="kit-empty">
        <i class="ph {{ auth()->user()->is_approved ? 'ph-check-circle' : 'ph-hourglass' }}"></i>
        <h3>{{ auth()->user()->is_approved ? 'Approved' : 'Pending Review' }}</h3>
        <p class="kit-muted">Keep your company information complete so the admin team can verify your account quickly.</p>
    </div>
</section>
@endsection
