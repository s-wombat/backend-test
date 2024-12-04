<?php

namespace App\Models;

use App\Enums\TaskStatuses;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'project_id',
        'user_id',
        'status',
        'deadline',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => TaskStatuses::class,
    ];

    /**
     * Проект, к которому относится задача.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Пользователь, которому назначена задача.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

