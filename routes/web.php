<?php

use App\Http\Controllers\API\BlogPostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/password-reset/{token}', function ($token) {
    return response()->json(['token' => $token, 'message' => 'Please reset your password in the app.']);
})->name('password.reset');

Route::get(
    '/detail-posts/{id}',
    [BlogPostController::class, 'sharePosts']
)->name('posts.share');
