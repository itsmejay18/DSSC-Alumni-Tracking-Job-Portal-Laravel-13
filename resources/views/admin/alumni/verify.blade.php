@extends('layouts.admin')

@section('page-title', 'Verify Alumni')

@section('dashboard-content')
<section class="kit-card pad-lg">
    <div class="kit-card-head">
        <div>
            <h2>Verification Review</h2>
            <p class="kit-card-subtitle">Confirm alumni identity and graduate record integrity.</p>
        </div>
    </div>
    <form method="POST" action="{{ route('admin.alumni.verify.store', $alumni) }}" class="kit-form-grid">
        @csrf
        <div class="kit-field">
            <label for="is_verified">Verification status</label>
            <select id="is_verified" name="is_verified">
                <option value="1" @selected($alumni->alumniProfile?->is_verified)>Verified</option>
                <option value="0" @selected(! $alumni->alumniProfile?->is_verified)>Pending</option>
            </select>
        </div>
        <div class="kit-action-row">
            <button class="kit-button primary" type="submit">Update verification</button>
            <a class="kit-button secondary" href="{{ route('admin.alumni.show', $alumni) }}">Back</a>
        </div>
    </form>
</section>
@endsection
