<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ApproveEmployerRequest;
use App\Models\ActivityLog;
use App\Models\Employer;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmployerController extends Controller
{
    public function __construct(protected NotificationService $notificationService)
    {
    }

    public function index(Request $request)
    {
        $employers = Employer::query()
            ->with(['user', 'industry'])
            ->when($request->boolean('pending_only'), fn ($query) => $query->where('is_verified', false))
            ->latest()
            ->paginate(config('settings.pagination.per_page', 20))
            ->withQueryString();

        return view('admin.employers.index', compact('employers'));
    }

    public function show(Employer $employer)
    {
        return view('admin.employers.show', [
            'employer' => $employer->load(['user', 'industry', 'jobs']),
        ]);
    }

    public function approve(ApproveEmployerRequest $request, Employer $employer): RedirectResponse
    {
        $employer->update([
            'is_verified' => true,
            'verification_date' => now(),
            'verified_by' => $request->user()->id,
        ]);

        $employer->user?->update(['is_approved' => true]);
        $this->notificationService->sendEmployerApproved($employer->user);

        ActivityLog::record($request->user(), 'update', 'employer', 'Approved employer account.');

        return back()->with('success', 'Employer approved successfully.');
    }

    public function reject(ApproveEmployerRequest $request, Employer $employer): RedirectResponse
    {
        $employer->update([
            'is_verified' => false,
            'verification_date' => null,
            'verified_by' => null,
        ]);

        $employer->user?->update(['is_approved' => false]);

        ActivityLog::record($request->user(), 'update', 'employer', 'Rejected employer account.');

        return back()->with('success', 'Employer approval has been rejected.');
    }
}
