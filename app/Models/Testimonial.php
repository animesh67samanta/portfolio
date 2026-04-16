<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'client_name',
    'client_role',
    'company',
    'avatar_path',
    'quote',
    'rating',
    'status',
    'sort_order',
])]
class Testimonial extends Model
{
    use HasFactory;

    /**
     * Scope for published testimonials.
     */
    public function scopePublished($query): Builder
    {
        return $query->where('status', 'published');
    }
}
