<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\JobRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function __construct(
        private readonly JobRepository $repository
    ) {
    }

    public function listJobs(Request $request): JsonResponse
    {
        $limit = $request->query('limit', 10);

        return response()->json(['data' =>  $this->repository->getJobs((int)$limit)]);
    }
}
