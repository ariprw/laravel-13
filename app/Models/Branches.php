<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branches extends Model
{
    use HasUuids;

    protected $table = 'branches';

    protected $fillable = [
        'name',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when(isset($filters['status']) && $filters['status'] !== '', function ($query) use ($filters) {
            $query->where('is_active', $filters['status']);
        });

        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            });
        });

        return $query;
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'branch_id', 'id');
    }

    public static function getWith()
    {
        return [];
    }
}