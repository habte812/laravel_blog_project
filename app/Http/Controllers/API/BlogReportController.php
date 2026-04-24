<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogReport;
use Illuminate\Http\Request;

class BlogReportController extends Controller
{
    public function index() {}

    public function store(Request $request, $blogId)
    {
        $userReporter = auth('sanctum')->id();
        $theBlog = BlogPost::find($blogId);
        if (!$theBlog) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Blog not found'
            ], 404);
        }
        $alreadyReported = BlogReport::where('blog_id', $blogId)->where('user_id', $userReporter)->exists();
        if ($alreadyReported) {
            return response()->json([
                'status' => 'Error',
                'message' => 'You have already reported this post. We are reviewing it!'
            ], 400);
        }

        BlogReport::create([
            'blog_id' => $blogId,
            'user_id' => $userReporter,
            'reason' => $request->reason,
            'details' => $request->details,
        ],);
        return response()->json(['status' => 'Success', 'message' => 'Reported'], 201);
    }

    public function show(string $id) {}

    public function update(Request $request, string $id) {}

    public function destroy(string $id) {}
}
