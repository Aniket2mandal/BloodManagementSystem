<?php

namespace App\Helpers;

class GeoHelper
{
    // Get coordinates (lat, lng) for any location string using OpenStreetMap Nominatim
    public static function getCoordinates($location)
    {
        $location = urlencode($location);
        $url = "https://nominatim.openstreetmap.org/search?q={$location}&format=json&limit=1";
        // dd($url);

        $opts = [
            "http" => [
                "header" => "User-Agent: LaravelApp/1.0\r\n"
            ]
        ];
        $context = stream_context_create($opts);
        $response = file_get_contents($url, false, $context);

        $json = json_decode($response);

        if (!empty($json[0])) {
            return [
                'lat' => floatval($json[0]->lat),
                'lng' => floatval($json[0]->lon),
            ];
        }

        return null;
    }

    // Haversine distance calculation in kilometers
    public static function haversineDistance($lat1, $lon1, $lat2, $lon2, $earthRadius = 6371)
    {
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $dLat = $lat2 - $lat1;
        $dLon = $lon2 - $lon1;

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos($lat1) * cos($lat2) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        // print_r($c.',');

        return $earthRadius * $c;  // distance in km
    }
}
