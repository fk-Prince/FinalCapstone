<?php

namespace App\Service\Utils;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class NominatimService
{
    public function __construct() {}

    public function reverse(float $lat, float $lon, int $zoom = 18)
    {
        $cacheKey = "reverse_geo:" . round($lat, 5) . ":" . round($lon, 5) . ":z{$zoom}";

        return Cache::remember($cacheKey, now()->addDays(7), function () use ($lat, $lon, $zoom) {
            $response = Http::withHeaders([
                'User-Agent' => config('app.name') . ' prince.sestoso@gmail.com',
                'Accept-Language' => 'en',
            ])->get('https://nominatim.openstreetmap.org/reverse', [
                'format'         => 'json',
                'lat'            => $lat,
                'lon'            => $lon,
                'zoom'           => $zoom,
                'addressdetails' => 1,
            ]);

            if ($response->failed()) {
                throw new \Exception('Nominatim request failed: ' . $response->body());
            }

            return $response->json();
        });
    }

    public function nearestStreet(float $lat, float $lon)
    {
        $cacheKey = "nearest_street:" . round($lat, 5) . ":" . round($lon, 5);

        return Cache::remember($cacheKey, now()->addDays(7), function () use ($lat, $lon) {
            $query = <<<OVERPASS
        [out:json][timeout:10];
        way(around:200,{$lat},{$lon})[highway][name];
        out geom 1;
        OVERPASS;

            $response = Http::withHeaders([
                'User-Agent' => config('app.name') . ' prince.sestoso@gmail.com',
            ])->post('https://overpass-api.de/api/interpreter', [
                'data' => $query,
            ]);

            if ($response->failed()) {
                throw new \Exception('Overpass request failed: ' . $response->body());
            }

            $elements = $response->json()['elements'] ?? [];

            $streetName = collect($elements)
                ->filter(fn($el) => !empty($el['tags']['name']))
                ->first()['tags']['name'] ?? null;

            return response()->json([
                'success' => true,
                'street' => $streetName,
            ]);
        });
    }
}
