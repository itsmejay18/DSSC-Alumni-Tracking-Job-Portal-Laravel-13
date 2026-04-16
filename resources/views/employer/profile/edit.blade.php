@extends('layouts.employer')

@section('page-title', 'Company Profile')

@section('dashboard-content')
<section class="kit-card pad-lg">
    <form method="POST" action="{{ route('employer.profile.update') }}" enctype="multipart/form-data" class="kit-form-grid">
        @csrf
        @method('PUT')
        <div class="kit-field"><label for="name">Account Name</label><input id="name" type="text" name="name" value="{{ old('name', $employer->name) }}" required></div>
        <div class="kit-field"><label for="email">Email</label><input id="email" type="email" name="email" value="{{ old('email', $employer->email) }}" required></div>
        <div class="kit-field"><label for="company_name">Company Name</label><input id="company_name" type="text" name="company_name" value="{{ old('company_name', $employer->employerProfile?->company_name) }}" required></div>
        <div class="kit-field">
            <label for="industry_id">Industry</label>
            <select id="industry_id" name="industry_id">
                <option value="">Select industry</option>
                @foreach ($industries as $industry)
                    <option value="{{ $industry->id }}" @selected(old('industry_id', $employer->employerProfile?->industry_id) == $industry->id)>{{ $industry->industry_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="kit-field"><label for="phone">Phone</label><input id="phone" type="text" name="phone" value="{{ old('phone', $employer->employerProfile?->phone) }}"></div>
        <div class="kit-field"><label for="address_line1">Address Line 1</label><input id="address_line1" type="text" name="address_line1" value="{{ old('address_line1', $employer->employerProfile?->address_line1) }}"></div>
        <div class="kit-field"><label for="city">City</label><input id="city" type="text" name="city" value="{{ old('city', $employer->employerProfile?->city) }}"></div>
        <div class="kit-field"><label for="province">Province</label><input id="province" type="text" name="province" value="{{ old('province', $employer->employerProfile?->province) }}"></div>
        <div class="kit-field"><label for="company_logo">Company Logo</label><input id="company_logo" type="file" name="company_logo" accept="image/*"></div>
        <div class="kit-action-row"><button class="kit-button primary" type="submit">Save Profile</button></div>
    </form>
</section>
@endsection
