<?php

namespace App\Services;

use App\Entity\Region;
use App\Enum\JobStatusEnum;
use App\Jobs\RefreshDataJob;
use App\Repositories\JobRepository;
use App\Repositories\RegionRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DataService
{
    private const CACHE_TTL = 3600;
    private const SEARCH_CACHE_KEY = 'region_%s_%s';

    public function __construct(
        private readonly JobRepository $jobRepository,
        private readonly RegionRepository $regionRepository
    ) {
    }

    public function refreshData(Request $request)
    {
        $action = $request->query('action');
        $delaySeconds = (int)$request->query('delaySeconds', 0);

        if ($action !== 'refresh') {
            return response()->json(['error' => 'Invalid action'], 400);
        }

        $job = $this->jobRepository->createJob(
            Carbon::now()->timestamp,
            $delaySeconds,
            JobStatusEnum::JOB_STATUS_NOT_COMPLETED->value
        );

        RefreshDataJob::dispatch($job->getId())->delay(now()->addSeconds($delaySeconds));

        return response()->json(['data' => ['success' => true]]);
    }

    public function searchData(Request $request)
    {
        $lat = $request->query('lat');
        $lon = $request->query('lon');

        $cacheKey = sprintf(self::SEARCH_CACHE_KEY, $lat, $lon);

        $cachedData = Cache::get($cacheKey);
        if ($cachedData) {
            return response()->json(['data' => ['geo' => ['oblast' => $cachedData->getName()], 'cache' => 'hit']]);
        }

        $data = $this->regionRepository->findByCoordinates((float)$lat, (float)$lon);

        if ($data) {
            Cache::put($cacheKey, $data, self::CACHE_TTL);
            return response()->json(['data' => ['geo' => ['oblast' => $data->getName()], 'cache' => 'miss']]);
        }

        return response()->json(['error' => 'Region not found'], 404);
    }

    public function purgeData()
    {
        Cache::flush();

        return response()->json(['data' => ['status' => 'success']]);
    }
}
