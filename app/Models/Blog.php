<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'image', 'content', 
        'read_length', 'posted_at', 'likes', 
        'share_link'
    ];

    protected $casts = [
        'posted_at' => 'datetime',
    ];
    
    public function comments()
    {
        return $this->hasMany(BlogComment::class);
    }

    public function categories()
    {
        return $this->belongsToMany(BlogCategory::class, 'blog_category_blog');
    }
}
