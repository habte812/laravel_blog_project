<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use function Laravel\Prompts\select;

class SavedBlogsController extends Controller
{
    public function toggleSave($blog_id)
    {
        $user = auth('sanctum')->user();
        if (!BlogPost::where('id', $blog_id)->exists()) {
            return response()->json([
                'status' => 'Error',
                'message' => "Blog not found"
            ], 404);
        }
        $status = $user->savedBlogs()->toggle($blog_id);

        $isSaved = count($status['attached']) > 0;
        return response()->json([
            'status' => 'Success',
            'is_saved' => $isSaved,
        ], 200);
    }



    public function getMySavedBlogs(Request $request)
    {
        $user = auth('sanctum')->user();
        $blogs = $user->savedBlogs()->with(['author:id,name,profile_picture', 'category:id,name,category_image'])
        ->select([
            'blog_posts.id', 
            'blog_posts.user_id', 
            'blog_posts.category_id', 
            'blog_posts.excerpt', 
            'blog_posts.title', 
            'blog_posts.thumbnail', 
            'blog_posts.published_at'
        ])
        ->latest('saved_blogs.created_at')
        ->cursorPaginate(5);
    $blogs->getCollection()->transform(function ($blog) {
        return $blog->makeHidden(['content', 'pivot', 'status', 'user_id', 'category_id','share_count','commet_count']);
    });
        return response()->json([
            'status' => 'Success',
            'blogs' => $blogs->items(),
            'next_cursor' => $blogs->nextCursor()?->encode(),
            'has_more_pages' => $blogs->hasMorePages(),
            'layout'=>[]
        ], 200);
    }
}
