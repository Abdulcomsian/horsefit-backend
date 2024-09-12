<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Auth\FriendAndFollowerController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
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

    Route::post('logout', [LogoutController::class, 'logout'])->name('logout');

    Route::post('post/store', [PostController::class, 'storePost'])->name('post.store');
    Route::post('post/toggle-like', [PostController::class, 'togglePostLike'])->name('post.toggle-like');
    Route::post('post/comment/store', [PostController::class, 'postComment'])->name('post.comment.store');

    Route::post('/friend-request', [FriendAndFollowerController::class, 'sendFriendRequest'])->name('friend-request');
    Route::post('/respond-friend-request', [FriendAndFollowerController::class, 'respondToFriendRequest'])->name('respond-friend-request');
    Route::post('/user/toggle-follow', [FriendAndFollowerController::class, 'togglefollowUser'])->name('user.toggle-follow');
    Route::get('/user/followers', [FriendAndFollowerController::class, 'getAllFollowers'])->name('user.followers');
    Route::get('/user/followings', [FriendAndFollowerController::class, 'getAllFollowings'])->name('user.followings');
    Route::get('/user/pending-friend-requests', [FriendAndFollowerController::class, 'getUserPendingFriendRequest'])->name('user.pending-friend-requests');
    Route::get('/user/get-friend-requests', [FriendAndFollowerController::class, 'getUserReceivedFriendRequest'])->name('user.get-friend-requests');
});