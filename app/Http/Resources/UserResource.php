<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
        'id' => $this->id,
        'name' => $this->name,
        'email' => $this->email,
        'bio'=>$this->bio,
        'is_verified'=> $this->hasVerifiedEmail(),
        'profile_picture' => $this->profile_picture, 
        'profile_picture_url'=>$this->profile_picture_url,
        'posts_count'=>$this->whenCounted('blog_posts'),
        'followings_count'=>$this->whenCounted('followings'),
        'followers_count'=>$this->whenCounted('followers'),
        'is_following'=> $request->user('sanctum')? $this->followers()->where('follower_id', $request->user('sanctum')->id)->exists():false,
        'joined_at' => $this->created_at->format('M d, Y'),
        
    ];
    }
}
