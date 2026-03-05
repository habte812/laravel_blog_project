<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function react(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|integer|exists:blog_posts,id',
            'like_status' => 'required|integer|in:1'
        ]);
        if ($validator->fails()):
            return response()->json([
                'status' => "Error",
                'message' => $validator->errors()
            ], 400);
        endif;

        $userId = Auth::id();
        $like = Like::where('user_id', $userId)->where('post_id', $request->post_id)->first();
        $post = BlogPost::find($request->post_id);
        if ($like) {
            if ($post && $post->like_count > 0) {
                $post->decrement('like_count');
            }
            $like->delete();
            return response()->json([
                'status' => "Success",
                'message' => 'Unliked'
            ], 200);
        } else {
            Like::create([
                'post_id' => $request->post_id,
                'user_id' => $userId,
                'like_status' => $request->like_status
            ]);
            $post->increment('like_count');
            return response()->json([
                'status' => "Success",
                'message' => 'Liked'
            ], 201);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
