<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BlogPostController;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $stats = [
        'posts' => BlogPost::count(),
        'users' => User::count(),
    ];
    return view('welcome', compact('stats'));
});

Route::get('/password-reset/{token}', function ($token) {
    return view('password_reset', ['token' => $token]);
})->name('password.reset');

Route::get(
    '/detail-posts/{id}',
    [BlogPostController::class, 'sharePosts']
)->name('posts.share');

Route::get(
    '/author-profile/{id}',
    [AuthController::class, 'shareAuthorProfile']
)->name('profile.share');