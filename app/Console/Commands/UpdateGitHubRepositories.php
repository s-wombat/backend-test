<?php

namespace App\Console\Commands;

use App\Jobs\UpdateGitHubRepositoriesJob;
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
            UpdateGitHubRepositoriesJob::dispatch($project);
            $this->info("Dispatched job for project: {$project->name}");
        }

        $this->info('All jobs dispatched successfully.');
    }
}

