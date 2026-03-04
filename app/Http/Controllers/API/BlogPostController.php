<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use function Symfony\Component\Clock\now;

class BlogPostController extends Controller
{
    public function index()
    {
        $posts = BlogPost::get(['id', 'title', 'excerpt', 'thumbnail', 'published_at']);
        return response()->json([
            'status' => 'Success',
            'count' => $posts->count(),
            'data' => $posts
        ], 200);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:blog_categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'thumbnail' => 'nullable|image|max:2048',
            'hashtags' => 'nullable'
        ]);

        if ($validator->fails()):
            return response()->json([
                'status' => 'Error',
                'message' => $validator->errors()
            ], 400);
        endif;


        try {
            $thumbnailPath = null;
            if ($request->hasFile('thumbnail')):
                $thumbnailPath = $request->file('thumbnail')->store('blog_thumbnail', 'public');
            endif;

            $loggedinUser = Auth::user();
            $post =    BlogPost::create([
                'user_id' => $loggedinUser->id,
                'category_id' => $request->category_id,
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'content' => $request->content,
                'excerpt' => Str::limit(strip_tags($request->content), 150),
                'thumbnail' => $thumbnailPath,
                'status' => $loggedinUser->role == 'admin' ? 'published' : 'draft',
                'published_at' =>  date('Y-m-d H:i:s')
            ]);
            $category = BlogCategory::find($request->category_id);
            $allTags = str_replace(' ','',$category->name) . ',' . str_replace(' ','',$request->hashtags);
            $tagedHashs = explode(',', $allTags);
            $finalKeywords = collect($tagedHashs)->map(fn($tag) => trim(str_replace('#', '', $tag)))->implode(', ');
            $post->seo()->create([
                'post_id' => $post->id,
                'meta_title' => $post->title,
                'meta_description' => Str::limit(strip_tags($request->content), 160),
                'meta_keywords' => $finalKeywords
            ]);

            return response()->json([
                'status'  => 'Success',
                'message' => 'Post created successfully',
                'data'    => $post
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'Error',
                'message' => 'Something went wrong on the server.' . $e->getMessage()
            ], 500);
        }
    }

    public function show(string $id)
    {
        $post = BlogPost::with(['author:id,name,role,profile_picture','category:id,name', 'seo'])->find($id);

        if (!$post):
            return response()->json([
                'status' => "Error",
                'message' => 'Blog not found'
            ], 404);
        endif;
        return response()->json([
            'status' => 'Success',
            'data' => $post
        ]);
    }

    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        $post = BlogPost::find($id);
        if (!$post):
            return response()->json([
                'status' => "Error",
                'message' => 'Blog not found'
            ], 404);
        endif;

        if ($post->user_id !== $user->id && $user->role !== 'admin'):
            return response()->json([
                'status' => 'Error',
                'message' => 'Unauthorized access'
            ], 401);
        endif;

        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:blog_categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'thumbnail' => 'nullable|image|max:2048',
            'remove_thumbnail' => 'nullable',
            'hashtags' => 'nullable'
        ]);
        if ($validator->fails()):
            return response()->json([
                'status' => 'Error',
                'message' => $validator->errors()
            ], 422);
        endif;
        $data =  $request->only(['category_id', 'title', 'content']);

        try {

            if ($request->hasFile('thumbnail')):
                if ($post->thumbnail) {
                    Storage::disk('public')->delete($post->thumbnail);
                }
                $data['thumbnail'] = $request->file('thumbnail')->store('blog_thumbnail', 'public');
            elseif ($request->boolean('remove_thumbnail') === true) :
                if ($post->thumbnail) {
                    Storage::disk('public')->delete($post->thumbnail);
                }
                $data['thumbnail'] = null;
            endif;

            
            $data['slug'] = Str::slug($request->title);
            $data['excerpt'] = Str::limit(strip_tags($request->content), 150);
            $post->update($data);
            
            $category = BlogCategory::find($request->category_id);
            $allTags = str_replace(' ','',$category->name) . ',' . str_replace(' ','',$request->hashtags);
            $tagedHashs = explode(',', $allTags);
            $finalKeywords = collect($tagedHashs)->map(fn($tag) => trim(str_replace('#', '', $tag)))->implode(',');

            $post->seo()->update([
                'post_id' => $post->id,
                'meta_title' => $post->title,
                'meta_description' => Str::limit(strip_tags($request->content), 160),
                'meta_keywords' => $finalKeywords
            ]);
            return response()->json([
                'status' => 'Success',
                'message' => 'Post updated successfully',
                'data' => $post->fresh()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'Error',
                'message' => 'Update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        $user = Auth::user();
        $post = BlogPost::find($id);
        if (!$post):
            return response()->json([
                'status' => "Error",
                'message' => 'Blog not found'
            ], 404);
        endif;

        if ($user->id !== $post->user_id && $user->role !== 'admin'):
            return response()->json([
                'status' => 'Error',
                'message' => 'Unauthorized access'
            ], 401);
        endif;
        try {
            if ($post->thumbnail):
                Storage::disk('public')->delete($post->thumbnail);
            endif;
            $post->delete();
            return response()->json([
                'status' => "Success",
                'message' => 'Blog deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => "Error",
                'message' => 'Failed to delete the post.'
            ], 500);
        }
    }
}
