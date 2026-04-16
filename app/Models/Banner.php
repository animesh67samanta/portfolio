<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title',
    'subtitle',
    'image_path',
    'cta_text',
    'cta_url',
    'status',
    'sort_order',
    'published_at',
])]
class Banner extends Model
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
     * Scope for published banners.
     */
    public function scopePublished($query): Builder
    {
        return $query
            ->where('status', 'published')
            ->whereNotNull('published_at');
    }

    /**
     * Scope for active banners.
     */
    public function scopeActive($query): Builder
    {
        return $query->where('status', 'published');
    }
}
