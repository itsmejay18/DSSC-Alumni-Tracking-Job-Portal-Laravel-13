<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\JobApplied;
use App\Http\Controllers\Controller;
use App\Http\Requests\Alumni\ApplyJobRequest;
use App\Http\Resources\JobResource;
use App\Models\Job;
use App\Models\JobApplication;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    public function __construct(protected SearchService $searchService)
    {
    }

    public function index(Request $request)
    {
        return JobResource::collection($this->searchService->searchJobs($request->all()));
    }

    public function show(Job $job): JobResource
    {
        return new JobResource($job->load(['employer', 'jobCategory']));
    }

    public function apply(ApplyJobRequest $request, Job $job)
    {
        $application = DB::transaction(function () use ($request, $job) {
            $resumePath = $request->file('resume')->store('resumes', 'public');

            $application = JobApplication::query()->create([
                'job_id' => $job->id,
                'alumni_id' => $request->user()->id,
                'cover_letter' => $request->string('cover_letter'),
                'resume_path' => $resumePath,
                'portfolio_link' => $request->string('portfolio_link'),
                'availability_date' => $request->date('availability_date'),
                'expected_salary' => $request->input('expected_salary'),
                'status' => 'pending',
            ]);

            $job->update(['applications_count' => $job->applications()->count()]);

            return $application;
        });

        event(new JobApplied($application));

        return response()->json(['message' => 'Application submitted successfully.'], 201);
    }
}
