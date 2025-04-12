<?php

namespace App\Controllers;

use App\Enums\JobStatus;
use App\Http\Request;
use App\Models\Job;
use Monolog\Level;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class JobController
{
    public function index(Request $request): Response
    {
        $data = Job::toSearch([
            'status' => $request->get('status'),
            'type' => $request->get('type'),
        ]);

        return response('jobs/index', $data);
    }

    public function executar(Job $job): RedirectResponse
    {
        try {
            resolveCallback($job->callable, $job->params);

            $job->update(['status' => JobStatus::DONE]);
        } catch (Throwable $exception) {
            logAppend($exception, Level::Alert);

            $job->update(['status' => JobStatus::FAILED]);
        }

        return redirectRoute('jobs');
    }
}
