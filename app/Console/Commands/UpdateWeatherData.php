<?php

namespace App\Console\Commands;

use App\Services\OpenWeatherService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class UpdateWeatherData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:update-weather-data';
    protected $signature = 'weather:update {city}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and update weather data for a specific city';

    protected $weatherService;

    /**
     * Конструктор команды.
     */
    public function __construct(OpenWeatherService $weatherService)
    {
        parent::__construct();
        $this->weatherService = $weatherService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $city = $this->argument('city');

        $this->info("Fetching weather data for city: {$city}");

        $weatherData = $this->weatherService->getCurrentWeather($city);

        if (isset($weatherData['error'])) {
            $this->error("Failed to fetch weather data: " . $weatherData['error']);
            return;
        }

        // Кэшируем данные
        Cache::put("weather_{$city}", $weatherData, now()->addHour());

        $this->info("Weather data for {$city} updated successfully.");
    }
    
}
