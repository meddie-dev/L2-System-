<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeocodeController extends Controller
{
    public function getAutocomplete($query)
    {
        // Get the API key from .env
        $apiKey = env('OPENROUTESERVICE_API_KEY'); 

        // Define the API URL
        $url = "https://api.openrouteservice.org/geocode/autocomplete";

        // Make the request to OpenRouteService API
        $response = Http::get($url, [
            'api_key' => $apiKey,
            'text' => $query
        ]);

        // Check if the request was successful
        if ($response->successful()) {
            return response()->json($response->json()); // Return JSON response
        } else {
            return response()->json(['error' => 'API request failed'], $response->status());
        }
    }
}
