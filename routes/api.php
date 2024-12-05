<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\GitHubController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WeatherController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('auth')->controller((AuthController::class))->group(function () {
    Route::post('/login', 'login')->name('login');
   
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', 'logout')->name('logout');
        Route::post('/refresh', 'refreshToken')->name('refresh');
});
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResources([
        'users' => UserController::class,
        'projects' => ProjectController::class,
        'tasks' => TaskController::class,
    ]);

    Route::get('/weather', [WeatherController::class, 'getCachedWeather']);
    Route::get('/projects/{projectId}/repositories', [GitHubController::class, 'getRepositories']);
});