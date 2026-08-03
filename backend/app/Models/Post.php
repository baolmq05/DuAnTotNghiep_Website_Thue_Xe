<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enum\PostStatus;
use App\Models\PostCategory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'thumbnail',
        'seo_keywords',
        'user_id',
        'post_category_id',
        'status',
        'type',
        'published_at',
    ];

    protected $casts = [
        'status' => PostStatus::class,
    ];

    protected static function booted()
    {
        static::creating(function ($post) {
            if (empty($post->published_at)) {
                $post->published_at = now();
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PostCategory::class, 'post_category_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
