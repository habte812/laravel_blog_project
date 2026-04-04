<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/password-reset/{token}', function ( $token){
    return response()->json(['token' => $token, 'message' => 'Please reset your password in the app.']);
})->name('password.reset');

Route::get('/detail-posts/{id}',function($id){
    return response()->json(['id' => $id, 'message' => 'Post detail.']);
})->name('posts.share');