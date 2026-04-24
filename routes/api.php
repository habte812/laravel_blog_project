<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BlogCategoryController;
use App\Http\Controllers\API\BlogPostController;
use App\Http\Controllers\API\BlogReportController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\EmailVerificationController;
use App\Http\Controllers\API\FollowController;
use App\Http\Controllers\API\LikeController;
use App\Http\Controllers\API\PostViewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register',[AuthController::class, 'register'])->name('register');
Route::post('/login',[AuthController::class, 'login'])->name('login');

Route::post('/password/forget-password', [AuthController::class, 'forgetPassword'])->name('password.email');
Route::post('/password/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
Route::get('/posts/{user}/author-profile', [AuthController::class,'getAuthorProfile'])->name('posts.authorProfile');



Route:: middleware(['auth:sanctum','throttle:api'])->group( function (){
Route::get('/profile',[AuthController::class, 'profile'])->name('user.profile');
Route::post('/logout', [AuthController::class, 'logout'])->name('user.logout');
Route::post('/profile/update',[AuthController::class,'updateProfile'])->name('user.update_profile');
Route::post('/password/change-password', [AuthController::class, 'changePassword'])->name('password.change');

Route::apiResource('/categories', BlogCategoryController::class)->middleware(['role:admin'])->except(['index']);


Route::post('/email/verification',[EmailVerificationController::class, 'sendEmailVerification'])->name('verification.send');
Route::get('/verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');


Route::post('/posts/{user}/follow',[FollowController::class,'toggleFollow'])->name('posts.follow');
Route::post('/posts/{blogId}/report',[BlogReportController::class,'store'])->name('posts.report');

Route::middleware('verified')->group(function(){
Route::apiResource('/posts', BlogPostController::class)->middleware(['role:admin,author'])->except(['index','show']);
Route::post('/posts/reaction',[LikeController::class, 'react'])->name('posts.react');
Route::apiResource('/posts/comments', CommentController::class);
});

});



Route::get('/categories', [BlogCategoryController::class, 'index'])->name('categories.index');
Route::get('/posts', [BlogPostController::class, 'index'])->name('posts.index');
Route::get('/posts/{id}', [BlogPostController::class, 'show'])->name('posts.show');
Route::get('/posts/{post_id}/comments', [CommentController::class, 'show'])->name('comments.show');
Route::get('/posts/{comment_id}/replies', [CommentController::class, 'getReplies'])->name('comments.getReplies');
Route::post('/posts/views', [PostViewController::class, 'postviews'])->name('posts.views');