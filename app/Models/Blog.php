<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title',
    'slug',
    'excerpt',
    'content',
    'featured_image',
    'cover_image',
    'status',
    'published_at',
])]
class Blog extends Model
{
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    /**
     * Scope for published blogs.
     */
    public function scopePublished($query): Builder
    {
        return $query
            ->where('status', 'published')
            ->whereNotNull('published_at');
    }
}
