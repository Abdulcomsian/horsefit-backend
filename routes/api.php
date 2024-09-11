<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Auth\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Routes without auth
Route::group(['prefix' => 'v1', 'as' => 'api.'], function () {

    Route::get('get-roles-list', [AuthController::class, 'getRoleList'])->name('get-roles-list');
    
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');

});

// Routes using auth
Route::group(['prefix' => 'v1', 'as' => 'api', 'middleware' => ['auth:sanctum']], function () {

    Route::post('post/store', [PostController::class, 'storePost'])->name('post.store');
    Route::post('post/toggle-like', [PostController::class, 'togglePostLike'])->name('post.toggle-like');
    Route::post('post/comment/store', [PostController::class, 'postComment'])->name('post.comment.store');
});