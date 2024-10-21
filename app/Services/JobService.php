<?php

namespace App\Services;

use App\Repositories\JobRepository;
use App\Jobs\ScrapeJob;

class JobService
{
    protected $jobRepository;

    public function __construct(JobRepository $jobRepository)
    {
        $this->jobRepository = $jobRepository;
    }

    public function createJob($jobData)
    {
        $jobId = $this->jobRepository->createJob($jobData);

        // Dispatch the scrape job for background processing
        dispatch(new ScrapeJob($jobId));

        return $jobId;
    }

    public function getJob($jobId)
    {
        $jobData = json_decode($this->jobRepository->getJob($jobId), true);
        return $jobData;
    }

    public function deleteJob($jobId)
    {
        return $this->jobRepository->deleteJob($jobId);
    }
}
