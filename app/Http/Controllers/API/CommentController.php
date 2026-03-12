<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{

    public function index() {}


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'post_id' => 'required|integer|exists:blog_posts,id',
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:comments,id'
        ]);
        if ($validator->fails()):
            return response()->json([
                'status' => 'Error',
                'message' => $validator->errors()
            ]);
        endif;

        try {
            $user = Auth::id();
            $role = Auth::user();
            $comment = Comment::create([
                'post_id' => $request->post_id,
                'user_id' => $user,
                'content' => $request->content,
                'parent_id' => $request->parent_id,
                'status' => $role->role == 'admin' ? 'approved' : 'pending'
            ]);
            $post = BlogPost::find($request->post_id);
            if ($post) {
                $post->increment('commet_count');
            }
            return response()->json([
                'status' => 'Success',
                'data' => $comment
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => "Error",
                'message' => 'Unknown error' . $e
            ], 500);
        }
    }


    public function show(string $post_id)
    {
        $comments = Comment::where('post_id', $post_id)
            ->whereNull('parent_id')
            ->with(['user:id,name,profile_picture'])
            ->latest()->get();

        return response()->json([
            'status' => 'Success',
            'count' => $comments->count(),
            'data' => $comments
        ]);
    }

    public function getReplies($comment_id)
    {
        $replies = Comment::where('parent_id', $comment_id)
            ->with(['user:id,name,profile_picture'])
            ->oldest()
            ->get();

        return response()->json([
            'status' => 'Success',
            'count' => $replies->count(),
            'data' => $replies
        ]);
    }


    public function destroy(string $id)
    {
        $comment =   Comment::find($id);
        if (!$comment) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Comment not found'
            ], 404);
        }

        $post = BlogPost::find($comment->post_id);
        $user = Auth::user();

        if ($user->id !== $comment->user_id && $user->role !== 'admin') {
            return response()->json([
                'status' => "Error",
                'message' => 'Unautherized access'
            ], 401);
        }

        try {
                $totalComment = 1 + $comment->replies()->count();
                $comment->delete();
                if ($post) {
                    $post->decrement('commet_count', $totalComment);
                }
          
            return response()->json([
                'status' => "Error",
                'message' => 'Deleted'
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'status' => "Error",
                'message' => 'Failed to delete the post.'
            ], 500);
        }
    }
}
