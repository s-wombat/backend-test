<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\GitHubService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GitHubController extends Controller
{
    public function __construct(
        protected GitHubService $gitHubService
        ) {}

    /**
     * Получение списка репозиториев для связанного проекта.
     *
     * @param int $projectId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRepositories(int $projectId)
    {

        $repositories = Cache::get("github_repositories_{$projectId}");

        if (!$repositories) {
            return response()->json(['error' => 'Repositories not available. Please try again later.'], 404);
        }

        return response()->json($repositories);
    }
}

