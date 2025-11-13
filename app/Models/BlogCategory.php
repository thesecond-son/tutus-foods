<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogCategory extends Model
{
    use HasFactory;

    protected $table = 'blog_categories';

    protected $fillable = ['name', 'slug'];

    public function blogs()
    {
        return $this->belongsToMany(Blog::class, 'blog_category_blog');
    }
}
