<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'images',
        'likes_count',
        'views',
        'is_published',
    ];

    protected $casts = [
        'images' => 'array',
        'is_published' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function incrementViews()
    {
        $this->increment('views');
    }

    public function incrementLikes()
    {
        $this->increment('likes_count');
    }
}
