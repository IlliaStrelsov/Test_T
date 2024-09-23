<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\RegionRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class NominatimFetchDataService
{
    public function __construct(
        private readonly RegionRepository $regionRepository
    ) {
    }

    public function processRegionDataFetch(): void
    {
        try {
            $client = new Client();
            $response = $client->get('https://nominatim.openstreetmap.org/search', [
                'query' => [
                    'q' => 'Області України',
                    'format' => 'json',
                    'addressdetails' => 1,
                    'polygon_geojson' => 1,
                ],
                'headers' => [
                    'User-Agent' => 'api_client',
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            foreach ($data as $region) {
                $lat = (float)$region['lat'];
                $lon = (float)$region['lon'];
                $name = $region['name'];

                if (!$this->regionRepository->existsByLatLon($lat, $lon)) {
                    $this->regionRepository->saveRegion($name, $lat, $lon);
                }
            }
        } catch (RequestException $e) {
            Log::error('Request failed', [
                'message' => $e->getMessage(),
                'request' => $e->getRequest()->getUri(),
                'response' => $e->hasResponse() ? (string) $e->getResponse()->getBody() : null,
            ]);
        } catch (\Exception $e) {
            Log::error('An error occurred', [
                'message' => $e->getMessage(),
            ]);
        }
    }
}
