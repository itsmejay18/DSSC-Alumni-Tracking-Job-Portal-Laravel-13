@extends('layouts.alumni')

@section('page-title', 'Employment History')

@section('dashboard-content')
<section class="kit-card pad-lg">
    <div class="kit-table-wrap">
        <table class="kit-table">
            <thead><tr><th>Date</th><th>Previous</th><th>New</th><th>Notes</th></tr></thead>
            <tbody>
            @forelse ($history as $entry)
                <tr>
                    <td>{{ optional($entry->change_date)->format('M d, Y') }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $entry->previous_status ?? 'none')) }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $entry->new_status ?? 'none')) }}</td>
                    <td>{{ $entry->notes }}</td>
                </tr>
            @empty
                <tr><td colspan="4">No employment changes logged yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $history->links() }}
</section>
@endsection
