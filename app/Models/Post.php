<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'slug',
        'body',
        'is_published',
        'published_date',
        'meta_description',
        'tags',
        'keywords'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_published' => 'boolean',
        'published_date' => 'datetime',
        'tags' => 'array',
        'keywords' => 'array',
    ];

    /**
     * Scope for published posts
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
                     //->whereDate('published_date', '<=', Carbon::today());
    }

    /**
     * Scope for draft posts
     */
    public function scopeDraft($query)
    {
        return $query->where('is_published', false);
    }
    
}
