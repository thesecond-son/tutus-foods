<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BlogComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_id',
        'name',
        'email',
        'comment',
    ];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}
