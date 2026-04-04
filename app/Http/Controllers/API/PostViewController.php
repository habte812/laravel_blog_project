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

    public function postviews(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:blog_posts,id'
        ]);
        try {
            $userId = Auth::id();
            $ip = $request->ip();
            
            $view = PostView::where('post_id', $request->post_id)
            ->when($userId, function ($query) use ($userId) {
                return $query->where('user_id', $userId);
            }, function ($query) use ($ip) {
                return $query->where('ip_address', $ip)->whereNull('user_id');
            })
            ->first();


          if(!$view){
         $view =    PostView::create([
                'user_id' => $userId,
                'post_id' => $request->post_id,
                'ip_address'=> $ip,
                'times_viewed'=>1
            ]);
             $view->post()->increment('view_count');
             return response()->json(['status' => 'Success', 'message' => 'New view recorded']);
          }




            if ($view->wasRecentlyCreated || $view->updated_at->diffInMinutes(now()) > 60) {
                $view->increment('times_viewed');
                $view->post()->increment('view_count');
                $view->touch();
                return response()->json([
                    'status' => 'Success',
                    'message'=> 'view updated'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'Error',
                'message' => $e->getMessage()
            ]);
        }
        return response()->json([
            'status' => 'Success',
            'message' => 'Already counted'
        ]);
    }
}
