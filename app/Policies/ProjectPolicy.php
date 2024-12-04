<?php

namespace App\Policies;

use App\Enums\RoleTypes;
use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Администратор имеет доступ ко всем проектам
        if ($user->role->name === RoleTypes::Admin) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        // Администратор имеет доступ ко всем проектам
        if ($user->role->name === RoleTypes::Admin) {
            return true;
        }

        // Менеджер имеет доступ только к проектам своей команды
        if ($user->role->name === RoleTypes::Manager && $user->id === $project->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Администратор и менеджер могут создавать проекты
        return in_array($user->role->name, [RoleTypes::Admin, RoleTypes::Manager]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        // Только администратор или менеджер, ответственный за проект, могут редактировать
        return $user->role->name === RoleTypes::Admin|| 
               ($user->role->name === RoleTypes::Manager && $user->id === $project->user_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        // Только администратор может удалять проекты
        return $user->role->name === RoleTypes::Admin;
    }

    /**
     * Determine whether the user can restore the model.
     */
    // public function restore(User $user, Project $project): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can permanently delete the model.
     */
    // public function forceDelete(User $user, Project $project): bool
    // {
    //     //
    // }
}
