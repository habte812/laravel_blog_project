<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function toggleFollow(User $user)
    {
        $me = auth('sanctum')->user();
        if ($me->id === $user->id) {
            return response()->json([
                'status' => 'Error',
                'message' => 'You cannot follow yourself'
            ], 422);
        }
        $result = $me->followings()->toggle($user->id);
        $isFollowing = count($result['attached']) > 0;

        return response()->json([
            'status' => 'Success',
            'is_following' => $isFollowing,
            'followers_count' => $user->followers()->count(),
            'message' => $isFollowing ? 'Followed successfully' : 'Unfollowed successfully'

        ], 200);
    }



    public function getMyFollowings()
    {

        $me = auth('sanctum')->user();
        $followings = $me->followings()
            ->select('users.id', 'users.name', 'users.profile_picture')->orderByPivot('created_at', 'desc')->cursorPaginate(10);
        $followings->getCollection()->transform(function ($user) {
            return $user->makeHidden(['pivot', 'bio', 'email', 'email_verified_at', 'email_verified_at', 'role', 'status', 'created_at', 'updated_at']);
        });

        if ($followings->isEmpty()) {
            return response()->json([
                'status' => 'Success',
                'message' => 'You are not following anyone',
                'followings' => $followings->items()
            ], 200);
        }
        return response()->json([
            'status' => 'Success',
            'followings' => $followings->items(),
            'next_cursor' => $followings->nextCursor()?->encode(),
            'has_more_pages' => $followings->hasMorePages(),

        ], 200);
    }

    public function getMyFollowers()
    {

        $me = auth('sanctum')->user();
        $followers = $me->followers()
            ->select('users.id', 'users.name', 'users.profile_picture')->orderByPivot('created_at', 'desc')->cursorPaginate(10);
        $followers->getCollection()->transform(function ($user) {
            return $user->makeHidden(['pivot', 'bio', 'email', 'email_verified_at', 'email_verified_at', 'role', 'status', 'created_at', 'updated_at']);
        });

        if ($followers->isEmpty()) {
            return response()->json([
                'status' => 'Success',
                'message' => 'You have no followers',
                'followers' => $followers->items(),
                'next_cursor' => $followers->nextCursor()?->encode(),
                'has_more_pages' => $followers->hasMorePages(),
            ], 200);
        }
        return response()->json([
            'status' => 'Success',
            'followers' => $followers->items(),
            'next_cursor' => $followers->nextCursor()?->encode(),
            'has_more_pages' => $followers->hasMorePages(),

        ], 200);
    }



    public function getMyFollowingsBlog(Request $request)
    {
        $me = auth('sanctum')->user();
        $followingsID = $me->followings()->pluck('users.id');
        $blogs = BlogPost::whereIn('user_id', $followingsID)
            ->with(['author:id,name,profile_picture', 'category:id,name'])
            ->select(
                'id',
                'user_id',
                'category_id',
                'excerpt',
                'title',
                'thumbnail',
                'published_at',
                 'view_count',
                 'like_count'
            )
            ->latest('published_at')
            ->cursorPaginate(5);

        if ($blogs->isEmpty()) {
            return response()->json([
                'status' => 'Success',
                'message' => 'No blogs from your network yet',
                'blogs' => $blogs->items(),
                'next_cursor' => $blogs->nextCursor()?->encode(),
                'has_more_pages' => $blogs->hasMorePages(),
                'layout'=>[]
            ], 200);
        }
        return response()->json([
            'status' => 'Success',
            'blogs' => $blogs->items(),
            'next_cursor' => $blogs->nextCursor()?->encode(),
            'has_more_pages' => $blogs->hasMorePages(),
            'layout'=>[]
        ], 200);
    }
}
