<?php

namespace App\Models;

use App\Enums\RoleTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

        /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'name' => RoleTypes::class,
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
