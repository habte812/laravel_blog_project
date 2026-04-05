<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use function Symfony\Component\Clock\now;

class BlogPostController extends Controller
{
    public function index(Request $request)
    {
        try {

            $perPage = 8;
            $posts = BlogPost::query()->with(['author:id,name,profile_picture', 'category:id,name,category_image'])
                ->select(['id', 'category_id', 'user_id', 'title', 'thumbnail', 'excerpt', 'view_count', 'like_count', 'published_at', 'created_at'])
                ->latest()->cursorPaginate($perPage);
            $items = $posts->items();
            $totalFetchedData = count($items);
            $layout = [];
            if ($totalFetchedData === 0):
                return $this->buildResponse([], [], $posts);
            endif;


            if (!$request->has('cursor')) {


                $topFourFeedsHor = min(4, $totalFetchedData);
                $layout[] = ['type' => 'horizontal_trending', 'count' => $topFourFeedsHor];
                $remaining = $totalFetchedData - $topFourFeedsHor;



                if ($remaining > 0) {
                    $bestTwoBlogs = min(2, $remaining);
                    $layout[] = ['type' => 'gradient_hero', 'count' => $bestTwoBlogs];
                    $remaining -= $bestTwoBlogs;
                }


                if ($remaining > 0) {
                    $layout[] = ['type' => 'standard_vertical', 'count' => $remaining];
                }

                if (count($layout) > 1) {
                    $hori = array_shift($layout);
                    shuffle($layout);
                    array_unshift($layout, $hori);
                }
            } else {
                $layout[] = ['type' => 'standard_vertical', 'count' => $totalFetchedData];
            }
            return $this->buildResponse($items, $layout, $posts);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Unable to fetch feed. Please try again later.'
            ], 500);
        }
    }
    private function buildResponse($items, $layout, $posts)
    {
        return response()->json([
            'status' => 'Success',
            'layout' => $layout,
            'data' => $items,
            'next_cursor' => $posts->nextCursor()?->encode(),
            'has_more_pages' => $posts->hasMorePages(),
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
                'excerpt' => $this->getExcerptOfContent($request->content),
                'thumbnail' => $thumbnailPath,
                'status' => $loggedinUser->role == 'admin' ? 'published' : 'draft',
                'published_at' =>  date('Y-m-d H:i:s')
            ]);
            $category = BlogCategory::find($request->category_id);
            $allTags = str_replace(' ', '', $category->name) . ',' . str_replace(' ', '', $request->hashtags);
            $tagedHashs = explode(',', $allTags);
            $finalKeywords = collect($tagedHashs)->map(fn($tag) => trim(str_replace('#', '', $tag)))->implode(',');
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
                'message' => 'Something went wrong on the server.'
            ], 500);
        }
    }
    private function getExcerptOfContent($content)
    {
        $theContent = json_decode($content, true);
        if (is_array($theContent)) {
            $plainText = '';
            foreach ($theContent as $block):
                if (isset($block['insert']) && is_string($block['insert'])) {
                    $plainText .= $block['insert'];
                }
            endforeach;
            return Str::limit(trim($plainText), 120);
        }
        return Str::limit($content, 120);
    }
    public function show(string $id)
    {
        $post = BlogPost::with(['author:id,name,role,profile_picture', 'category:id,name,category_image', 'seo'])->find($id);    
        if (!$post):
            return response()->json([
                'status' => "Error",
                'message' => 'Blog not found'
            ], 404);
        endif;
        $currentUser = auth('sanctum')->user();
        $post->is_owner = $currentUser?->id === $post->user_id;
        return response()->json([
            'status' => 'Success',
            'data' => $post
        ], 200);
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
        $data =  $request->only(['category_id', 'title', 'content', 'thumbnail']);

        try {

            if ($request->hasFile('thumbnail')):
                if ($post->thumbnail && Storage::disk('public')->exists($post->thumbnail)) {
                    Storage::disk('public')->delete($post->thumbnail);
                }
                $data['thumbnail'] = $request->file('thumbnail')->store('blog_thumbnail', 'public');
            elseif ($request->boolean('remove_thumbnail') === true) :
                if ($post->thumbnail && Storage::disk('public')->exists($post->thumbnail)) {
                    Storage::disk('public')->delete($post->thumbnail);
                }
                $data['thumbnail'] = null;
            endif;


            $data['slug'] = Str::slug($request->title);
            $data['excerpt'] = Str::limit(strip_tags($request->content), 150);
            $post->update($data);

            $category = BlogCategory::find($request->category_id);
            $allTags = str_replace(' ', '', $category->name) . ',' . str_replace(' ', '', $request->hashtags);
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
            if ($post->thumbnail && Storage::disk('public')->exists($post->thumbnail)):
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


    public function sharePosts(string $id)
    {
        $blog = BlogPost::with('seo') -> find($id);
        if (!$blog) {
        abort(404);
    }
    return view('share.post',compact('post'));
    }
}
