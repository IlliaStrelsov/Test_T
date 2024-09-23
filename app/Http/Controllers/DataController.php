<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enum\HttpMethods;
use App\Services\DataService;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function __construct(
        private readonly DataService $dataService,
    ) {
    }

    public function data(Request $request)
    {
        if ($request->isMethod(HttpMethods::METHOD_PUT->value)) {
            return $this->dataService->refreshData($request);
        } elseif ($request->isMethod(HttpMethods::METHOD_GET->value)) {
            return $this->dataService->searchData($request);
        } elseif ($request->isMethod(HttpMethods::METHOD_DELETE->value)) {
            return $this->dataService->purgeData();
        }

        return response()->json(['error' => 'Method not allowed'], 405);
    }
}
