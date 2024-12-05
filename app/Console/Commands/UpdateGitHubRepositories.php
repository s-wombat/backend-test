<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use App\Services\GitHubService;
use Illuminate\Support\Facades\Cache;

class UpdateGitHubRepositories extends Command
{
    /**
     * Название команды.
     *
     * @var string
     */
    protected $signature = 'github:update-repositories';

    /**
     * Описание команды.
     *
     * @var string
     */
    protected $description = 'Fetch and update GitHub repositories for all linked projects';

    public function __construct(protected GitHubService $gitHubService)
    {
        parent::__construct();
    }

    /**
     * Логика выполнения команды.
     */
    public function handle()
    {
        $this->info('Updating GitHub repositories for linked projects...');

        // Получение всех проектов с указанным GitHub владельцем
        $projects = Project::whereNotNull('github_owner')->get();

        foreach ($projects as $project) {
            $this->info("Fetching repositories for project: {$project->name}");

            // Получение репозиториев из GitHub API
            $repositories = $this->gitHubService->getRepositories($project->github_owner);

            if (isset($repositories['error'])) {
                $this->error("Failed to fetch repositories for project '{$project->name}': " . $repositories['error']);
                continue;
            }

            // Кэширование данных
            Cache::put("github_repositories_{$project->id}", $repositories, now()->addHour());

            $this->info("Repositories for project '{$project->name}' updated successfully.");
        }

        $this->info('GitHub repositories update completed.');
    }
}

