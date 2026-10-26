<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ActivityLog extends Model
{
    use HasUuids;

    protected $table = 'activity_log';

    protected $fillable = [
        'user_id',
        'log_name',
        'description',
        'subject_type',
        'subject_id',
        'properties',
    ];

    protected function casts(): array
    {
        return [
            'properties' => 'array',
        ];
    }

    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('log_name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")

                ->orWhereHas('user', function ($query) use ($search) {
                    $query->where('id', $search)
                    ->orWhere('name', 'like', "%{$search}%");
                })

                ->orWhereHas('user.branches', function ($query) use ($search) {
                    $query->where('id', $search)
                    ->orWhere('name', 'like', "%{$search}%");
                })

                ->orWhereHasMorph('subject', [User::class], function ($query) use ($search) {
                    $query->where('id', $search)
                    ->orWhere('name', 'like', "%{$search}%");
                })

                ->orWhereHasMorph('subject', [Branches::class], function ($query) use ($search) {
                    $query->where('id', $search)
                    ->orWhere('name', 'like', "%{$search}%");
                });

                // Kalau mau pencarian json di properties dengan case sensitive
                // ->orWhere('properties->old->name', 'like', "%{$search}%")
                // ->orWhere('properties->new->name', 'like', "%{$search}%");
            });
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function subject()
    {
        return $this->morphTo();
    }

    public static function getWith()
    {
        return ['user', 'user.branches', 'subject'];
    }
}