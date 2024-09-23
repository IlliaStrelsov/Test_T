<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enum\JobStatusEnum;
use App\Repositories\JobRepository;
use App\Services\NominatimFetchDataService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RefreshDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly int $jobId,
    ) {
    }

    public function handle(JobRepository $jobRepository, NominatimFetchDataService $dataService): void
    {
        $dataService->processRegionDataFetch();

        $job = $jobRepository->findJobById($this->jobId);

        $jobRepository->updateJobStatus($job, JobStatusEnum::JOB_STATUS_COMPLETED->value);
    }
}
