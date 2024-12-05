<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('telescope:prune')->daily();

        // Обновление данных о погоде для нескольких городов
        $cities = explode(', ', config('services.openweather.cities'));
        foreach ($cities as $city) {
            $schedule->command("weather:update {$city}")
                    ->hourly() // Обновление каждый час
                    ->appendOutputTo(storage_path('logs/weather_update.log')); // Логирование
        }

        // Обновление данных о репозиториях проекта
        $schedule->command('github:update-repositories')
             ->hourly() // Запуск каждый час
             ->appendOutputTo(storage_path('logs/github_update.log')); // Логирование
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
