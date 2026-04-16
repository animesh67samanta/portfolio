<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable([
    'title',
    'slug',
    'summary',
    'description',
    'thumbnail_path',
    'project_url',
    'repository_url',
    'started_at',
    'completed_at',
    'is_featured',
    'status',
    'published_at',
])]
class Project extends Model
{
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'started_at' => 'date',
            'completed_at' => 'date',
            'published_at' => 'datetime',
        ];
    }

    /**
     * Scope for featured projects.
     */
    public function scopeFeatured($query): Builder
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for published projects.
     */
    public function scopePublished($query): Builder
    {
        return $query->whereNotNull('published_at');
    }

    /**
     * @return BelongsToMany<Skill, $this>
     */
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class);
    }
}
