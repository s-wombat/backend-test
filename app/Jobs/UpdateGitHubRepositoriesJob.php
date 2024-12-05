<?php

namespace App\Jobs;

use App\Services\GitHubService;
use App\Models\Project;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateGitHubRepositoriesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param Project $project
     */
    public function __construct(
        protected Project $project
    ) {}

    /**
     * Execute the job.
     */
    public function handle(GitHubService $gitHubService): void
    {
        if (!$this->project->github_owner) {
            Log::error("GitHub owner not set for project: {$this->project->id}");
            return;
        }

        $repositories = $gitHubService->getRepositories($this->project->github_owner);

        if (isset($repositories['error'])) {
            Log::error("Failed to fetch repositories for project {$this->project->id}: " . $repositories['error']);
            return;
        }

        // Кэширование данных
        Cache::put("github_repositories_{$this->project->id}", $repositories, now()->addHour());

        Log::info("Repositories for project {$this->project->id} updated successfully.");
    }
}

