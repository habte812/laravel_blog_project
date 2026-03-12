<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BlogCategoryController;
use App\Http\Controllers\API\BlogPostController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\EmailVerificationController;
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
Route::get('/password/reset/{token}', function ($token){
    return response()->json(['token' => $token, 'message' => 'Please reset your password in the app.']);
})->name('password.reset');
Route::post('/password/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');




Route:: middleware('auth:sanctum')->group( function (){
Route::get('/profile',[AuthController::class, 'profile'])->name('profile');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/profile/update',[AuthController::class,'updateProfile'])->name('update_profile');
Route::post('/password/change-password', [AuthController::class, 'changePassword'])->name('password.change');

Route::apiResource('/categories', BlogCategoryController::class)->middleware(['role:admin']);
Route::post('/posts/views', [PostViewController::class, 'postviews'])->name('post_views');

Route::post('/email/verification',[EmailVerificationController::class, 'sendEmailVerification'])->name('verification.send');
Route::get('/verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');

Route::apiResource('/posts', BlogPostController::class)->middleware(['role:admin,author']);
Route::middleware('verified')->group(function(){
Route::post('/posts/reaction',[LikeController::class, 'react'])->name('react');
Route::apiResource('/posts/comments', CommentController::class);
});

});



Route::get('/categories', [BlogCategoryController::class, 'index'])->name('index');
Route::get('/posts', [BlogPostController::class, 'index'])->name('index');
Route::get('/posts/{post_id}/comments', [CommentController::class, 'show'])->name('comments.show');
Route::get('/posts/{comment_id}/replies', [CommentController::class, 'getReplies'])->name('comments.getReplies');
