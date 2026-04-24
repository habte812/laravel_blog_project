<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

use function PHPUnit\Framework\isNull;

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
        'content_updated_at'
    ];

protected $appends = ['time_ago', 'thumbnail_url', 'updated_content_at'];
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

public function updatedContentAt(): Attribute{
    return Attribute::make(
            get: function () {
                if(is_null($this->content_updated_at)){return null;}
                $date = Carbon::parse($this->content_updated_at);
                if ($date->gt(now()->subDays(7))) {
                    return $date->diffForHumans();
                }
                return $date->format('M j, Y');
            },
        );
}
}
