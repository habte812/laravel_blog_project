<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostView extends Model
{
     protected $fillable = [
        'post_id',
        'user_id',
        'times_viewed',
        'ip_address'
    ];
    public function post() {
    return $this->belongsTo(BlogPost::class, 'post_id');
}
}
