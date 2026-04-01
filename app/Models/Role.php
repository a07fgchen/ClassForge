<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['display_name', 'slug', 'description', 'scope', 'is_protected'])]
class Role extends Model
{
    public function casts(): array
    {
        return [
            'is_protected' => 'boolean',
            'updated_at' => 'datetime:Y-m-d H:i:s',
        ];
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user');
    }
}
