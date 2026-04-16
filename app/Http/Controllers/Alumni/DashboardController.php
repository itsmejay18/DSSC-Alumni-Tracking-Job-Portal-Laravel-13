<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user()->load(['alumniProfile.course', 'jobApplications.job', 'jobMatches.job', 'portalNotifications']);

        return view('alumni.dashboard', [
            'user' => $user,
            'stats' => [
                'applications' => $user->jobApplications()->count(),
                'matches' => $user->jobMatches()->count(),
                'interviews' => $user->jobApplications()->where('status', 'interviewed')->count(),
                'notifications' => $user->portalNotifications()->unread()->count(),
            ],
            'recentApplications' => $user->jobApplications()->with('job.employer')->latest()->take(5)->get(),
            'recentMatches' => $user->jobMatches()->with('job.employer')->latest('match_score')->take(5)->get(),
        ]);
    }

    public function notifications()
    {
        $notifications = auth()->user()->portalNotifications()->paginate(config('settings.pagination.per_page', 20));

        return view('alumni.notifications.index', compact('notifications'));
    }
}
