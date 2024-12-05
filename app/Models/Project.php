<?php

namespace App\Models;

use App\Enums\ProjectStatuses;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'github_owner',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => ProjectStatuses::class,
    ];

    /**
     * Менеджер проекта (связь с пользователем).
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Задачи, относящиеся к проекту.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
