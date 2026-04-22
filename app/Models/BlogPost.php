<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

protected $appends = ['time_ago', 'thumbnail_url'];
public function author(){
    return $this->belongsTo(User::class, 'user_id');
}

public function category(){
    return $this->belongsTo(BlogCategory::class, 'category_id');
}

public function seo(){
    return $this->hasOne(Seo::class, 'post_id');
}
// public function getThumbnailAttribute($value){
//     return $value? asset('storage/'.$value): null;
// }
public function getThumbnailUrlAttribute() 
{
    return $this->thumbnail ? asset('storage/' . $this->thumbnail) : null;
}
public function timeAgo(): Attribute{
 return Attribute::make(
            get: function () {
                $date = Carbon::parse($this->published_at);

                if ($date->gt(now()->subDays(7))) {
                    return $date->diffForHumans();
                }
                return $date->format('M j, Y');
            },
        );

}
}
