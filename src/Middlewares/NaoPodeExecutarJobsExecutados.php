<?php

namespace App\Middlewares;

use App\Models\Job;

class NaoPodeExecutarJobsExecutados
{
    public function __invoke(Job $job): bool
    {
        return ! $job->is_done;
    }
}
