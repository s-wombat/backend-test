<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GitHubService
{
    protected $baseUrl;
    protected $token;

    public function __construct()
    {
        $this->baseUrl = config('services.github.base_url');
        $this->token = config('services.github.token');
    }

    /**
     * Получение списка репозиториев пользователя или организации.
     *
     * @param string $owner
     * @return array
     */
    public function getRepositories(string $owner): array
    {
        $response = Http::withToken($this->token)->get("{$this->baseUrl}/users/{$owner}/repos");

        if ($response->successful()) {
            return $response->json();
        }

        return [
            'error' => $response->json()['message'] ?? 'Failed to fetch repositories.',
        ];
    }
}
