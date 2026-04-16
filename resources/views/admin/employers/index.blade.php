@extends('layouts.admin')

@section('page-title', 'Employers')

@section('dashboard-content')
<section class="kit-card pad-lg">
    <div class="kit-table-wrap">
        <table class="kit-table">
            <thead>
            <tr>
                <th>Company</th>
                <th>Industry</th>
                <th>Contact</th>
                <th>Status</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse ($employers as $employer)
                <tr>
                    <td>{{ $employer->company_name }}</td>
                    <td>{{ $employer->industry?->industry_name }}</td>
                    <td>{{ $employer->user?->email }}</td>
                    <td><span class="kit-badge {{ $employer->is_verified ? 'success' : 'warning' }}">{{ $employer->is_verified ? 'Verified' : 'Pending' }}</span></td>
                    <td><a class="kit-button secondary" href="{{ route('admin.employers.show', $employer) }}">View</a></td>
                </tr>
            @empty
                <tr><td colspan="5">No employers found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $employers->links() }}
</section>
@endsection
