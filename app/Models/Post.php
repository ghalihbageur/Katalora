<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'body',
        'thumbnail',
        'likes_count',
        'comments_count',
        'saves_count',
        'shares_count',
        'views',
        'author_id',
        'published_at',
        'status',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
