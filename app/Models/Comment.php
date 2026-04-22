<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'post_id',
        'user_id',
        'parent_id',
        'content',
    ];

protected $casts = [
    'id' => 'integer',
    'post_id' => 'integer',
    'user_id' => 'integer',
    'parent_id' => 'integer',
];
    protected $appends = ['updated_time_ago', 'created_time_ago'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
    public function createdTimeAgo(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->created_at->diffForHumans()
        );
    }
    public function updatedTimeAgo(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->updated_at->diffForHumans()
        );
    }
}
