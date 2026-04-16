@extends('layouts.admin')

@section('page-title', 'Settings')

@section('dashboard-content')
<form method="POST" action="{{ route('admin.settings.update') }}" class="portal-stack">
    @csrf
    @method('PUT')
    @foreach ($settings as $group => $entries)
        <section class="kit-card pad-lg">
            <div class="kit-card-head">
                <div>
                    <h2>{{ ucfirst($group) }} Settings</h2>
                    <p class="kit-card-subtitle">Adjust portal behavior for {{ $group }}.</p>
                </div>
            </div>
            <div class="kit-form-grid">
                @foreach ($entries as $setting)
                    <div class="kit-field">
                        <label for="setting-{{ $setting->id }}">{{ $setting->setting_key }}</label>
                        <input id="setting-{{ $setting->id }}" type="text" name="settings[{{ $setting->id }}]" value="{{ $setting->setting_value }}">
                    </div>
                @endforeach
            </div>
        </section>
    @endforeach
    <div class="kit-action-row"><button class="kit-button primary" type="submit">Save settings</button></div>
</form>
@endsection
