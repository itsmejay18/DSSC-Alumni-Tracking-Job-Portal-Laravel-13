@extends('layouts.alumni')

@section('page-title', 'Notifications')

@section('dashboard-content')
<section class="kit-card pad-lg">
    <div class="kit-list">
        @forelse ($notifications as $notification)
            <div class="kit-list-item">
                <div class="kit-list-meta">
                    <strong>{{ $notification->title }}</strong>
                    <span>{{ $notification->message }}</span>
                </div>
                <form method="POST" action="{{ route('notifications.read', $notification) }}">
                    @csrf
                    <button class="kit-button secondary" type="submit">{{ $notification->is_read ? 'Read' : 'Mark read' }}</button>
                </form>
            </div>
        @empty
            <div class="kit-empty"><i class="ph ph-bell"></i><h3>No notifications</h3></div>
        @endforelse
    </div>
    {{ $notifications->links() }}
</section>
@endsection
