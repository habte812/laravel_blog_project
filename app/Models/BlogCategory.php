<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    protected $fillable = [
        'name',
        'category_image',
        'slug'
    ];
 
    protected $appends = ['category_image_url'];
    public function getCategoryImageUrlAttribute(){
        return $this->category_image? asset('storage/'. $this->category_image):null;
    }



}
