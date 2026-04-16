<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = ActivityLog::query()
            ->with('user')
            ->when($request->filled('action'), fn ($query) => $query->where('action', $request->string('action')))
            ->when($request->filled('module'), fn ($query) => $query->where('module', $request->string('module')))
            ->latest('created_at')
            ->paginate(config('settings.pagination.per_page', 20))
            ->withQueryString();

        return view('admin.logs.activity', compact('logs'));
    }
}
