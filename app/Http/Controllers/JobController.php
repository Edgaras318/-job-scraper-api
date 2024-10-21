<?php

namespace App\Http\Controllers;

use App\DTO\JobDTO;
use App\Http\Requests\JobRequest;
use App\Services\JobService;
use Illuminate\Http\JsonResponse;

class JobController extends Controller
{
    protected $jobService;

    public function __construct(JobService $jobService)
    {
        $this->jobService = $jobService;
    }

    public function create(JobRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Create a DTO from the validated data
        $jobData = new JobDTO($validated['urls'], $validated['selectors']);

        $jobId = $this->jobService->createJob($jobData);

        return response()->json(['id' => $jobId], 201);
    }

    public function show(string $id): JsonResponse
    {
        $jobData = $this->jobService->getJob($id);

        if (!$jobData) {
            return response()->json(['error' => 'Job not found'], 404);
        }

        return response()->json($jobData);
    }

    public function destroy(string $id): JsonResponse
    {
        $deleted = $this->jobService->deleteJob($id);

        if ($deleted) {
            return response()->json(['message' => 'Job deleted'], 204);
        }

        return response()->json(['error' => 'Job not found'], 404);
    }
}
