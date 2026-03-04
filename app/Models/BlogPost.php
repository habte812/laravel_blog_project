<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'content',
        'excerpt',
        'thumbnail',
        'status',
        'published_at',
    ];


public function author(){
    return $this->belongsTo(User::class, 'user_id');
}

public function category(){
    return $this->belongsTo(BlogCategory::class, 'category_id');
}

public function seo(){
    return $this->hasOne(Seo::class, 'post_id');
}
}
