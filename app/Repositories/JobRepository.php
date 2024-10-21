<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Redis;

class JobRepository
{
    public function createJob($jobData)
    {
        $jobId = uniqid();
        Redis::set("job:$jobId", json_encode($jobData));
        return $jobId;
    }

    public function getJob($jobId)
    {
        return Redis::get("job:$jobId");
    }

    public function deleteJob($jobId)
    {
        return Redis::del("job:$jobId");
    }
}
