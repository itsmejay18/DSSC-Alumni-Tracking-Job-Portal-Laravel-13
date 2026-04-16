<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use App\Models\JobMatch;
use Illuminate\Http\RedirectResponse;

class MatchController extends Controller
{
    public function index()
    {
        $matches = auth()->user()
            ->jobMatches()
            ->with(['job.employer', 'job.jobCategory'])
            ->latest('match_score')
            ->paginate(config('settings.pagination.per_page', 20));

        return view('alumni.jobs.matched', compact('matches'));
    }

    public function markViewed(JobMatch $match): RedirectResponse
    {
        abort_unless($match->alumni_id === auth()->id(), 403);

        $match->update(['is_viewed' => true]);

        return back();
    }
}
