<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogReport extends Model
{
 protected $fillable = ['blog_id', 'user_id', 'reason', 'details', 'status'];

 public function  blog(){
    return $this->belongsTo(BlogPost::class);
 }
public  function  user(){
    return $this->belongsTo(User::class);
 }
}
