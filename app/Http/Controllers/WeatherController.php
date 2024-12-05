<?php

namespace App\Http\Controllers;

use App\Services\OpenWeatherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WeatherController extends Controller
{
    protected $weatherService;

    public function __construct(OpenWeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    /**
     * Получение текущей погоды для заданного города.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCachedWeather(Request $request)
    {
        $validated = $request->validate([
            'city' => 'required|string',
        ]);

        $city = $validated['city'];

        $weather = Cache::get("weather_{$city}");

        if (!$weather) {
            return response()->json(['error' => 'Weather data not available. Please try again later.'], 404);
        }

        return response()->json($weather);
    }
}
