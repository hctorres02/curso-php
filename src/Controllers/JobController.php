<?php

namespace App\Controllers;

use App\Enums\JobStatus;
use App\Models\Job;
use Monolog\Level;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Throwable;

class JobController
{
    public function executar(Job $job): RedirectResponse
    {
        try {
            resolveCallback($job->callable, $job->params);

            $job->update(['status' => JobStatus::DONE]);
        } catch (Throwable $exception) {
            logAppend($exception, Level::Alert);

            $job->update(['status' => JobStatus::FAILED]);
        }

        return redirect(route('jobs'));
    }
}
