<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'heading',
    'content',
    'photo_path',
    'resume_url',
    'status',
    'name',
    'email',
    'mobile',
    'current_address',
    'permanent_address',
    'experiences',
    'educations'
])]
class About extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'experiences' => 'array',
        'educations' => 'array',
    ];

    /**
     * Scope for published about.
     */
    public function scopePublished($query): Builder
    {
        return $query->where('status', 'published');
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo_path ? asset($this->photo_path) : null;
    }
}
