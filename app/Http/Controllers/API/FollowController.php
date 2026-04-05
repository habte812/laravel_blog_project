<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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

        ],200);
    }
}
