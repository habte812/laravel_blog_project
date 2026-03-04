<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BlogCategoryController;
use App\Http\Controllers\API\BlogPostController;
use App\Http\Controllers\API\CommentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register',[AuthController::class, 'register'])->name('register');
Route::post('/login',[AuthController::class, 'login'])->name('login');


Route:: middleware('auth:sanctum')->group( function (){

Route::get('/profile',[AuthController::class, 'profile'])->name('profile');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::apiResource('/categories', BlogCategoryController::class)->middleware(['role:admin']);
Route::apiResource('/posts', BlogPostController::class)->middleware(['role:admin,author']);
Route::apiResource('/comments', CommentController::class);
});


Route::get('/categories', [BlogCategoryController::class, 'index'])->name('index');
Route::get('/posts', [BlogPostController::class, 'index'])->name('index');
Route::get('/comments', [CommentController::class, 'index'])->name('index');
