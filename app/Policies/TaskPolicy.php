<?php

namespace App\Policies;

use App\Enums\RoleTypes;
use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Администратор и менеджер могут видеть все задачи
        return in_array($user->role->name, [RoleTypes::Admin, RoleTypes::Manager]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        // Администратор имеет доступ ко всем задачам
        if ($user->role->name === RoleTypes::Admin) {
            return true;
        }

        // Менеджер имеет доступ только к задачам своего проекта
        if ($user->role->name === RoleTypes::Manager && $user->id === $task->project->user_id) {
            return true;
        }

        // Пользователь имеет доступ только к задачам, назначенным ему
        if ($user->role->name === RoleTypes::User && $user->id === $task->user_id) {
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
    public function update(User $user, Task $task): bool
    {
        // Только администратор или менеджер, ответственный за проект, могут редактировать задачу
        return $user->role->name === RoleTypes::Admin || 
               ($user->role->name === RoleTypes::Manager && $user->id === $task->project->user_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        // Только администратор может удалять задачи
        return $user->role->name === RoleTypes::Admin;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Task $task): bool
    {
        // Администратор и менеджер могут создавать задачи
        return in_array($user->role->name, [RoleTypes::Admin, RoleTypes::Manager]);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    // public function forceDelete(User $user, Task $task): bool
    // {
    //     //
    // }
}
