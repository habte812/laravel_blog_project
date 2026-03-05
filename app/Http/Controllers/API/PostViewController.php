<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\PostView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Symfony\Component\Clock\now;

class PostViewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }
    public function show(string $id) {}
    /**
     * Display the specified resource.
     */
    public function postviews(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:blog_posts,id'
        ]);
        try {
        $userId = Auth::id();
        $view = PostView::firstorCreate([
            'user_id' => $userId,
            'post_id' => $request->post_id
        ]);
            if ($view->wasRecentlyCreated || $view->updated_at->diffInMinutes(now()) > 60) {
                $view->increment('times_viewed');
                $view->post()->increment('view_count');
                return response()->json([
                    'status' => 'Success',
                ]);
            }
        } catch (\Exception $e) {
             return response()->json([
                    'status' => 'Error',
                    'message'=> $e->getMessage()
                ]);
        }
        return response()->json([
            'status'=>'Success',
            'message'=>'Already counted'
        ]);
    }

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
