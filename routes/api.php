<?php

use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\FriendAndFollowerController;
use App\Http\Controllers\Api\V1\Auth\HorseController;
use App\Http\Controllers\Api\V1\Auth\PostController;
use App\Http\Controllers\Api\V1\Auth\UserController;
use App\Http\Controllers\Api\V1\Guest\AuthController as GuestAuthController;
use App\Http\Controllers\Api\V1\Guest\SocialAuthController;
use App\Http\Controllers\Api\V1\MediaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Routes without auth
Route::group(['prefix' => 'v1', 'as' => 'api.'], function () {

    Route::post('/upload-file', [MediaController::class, 'uploadFile'])->name('upload.file');
    Route::post('/delete-file', [MediaController::class, 'deleteFile'])->name('delete.file');

    Route::get('get-roles-list', [GuestAuthController::class, 'getRoleList'])->name('get-roles-list');
    
    Route::post('login', [GuestAuthController::class, 'login'])->name('login');
    Route::post('register', [GuestAuthController::class, 'register'])->name('register');
    Route::post('social-register', [SocialAuthController::class, 'socialRegister']);
    Route::post('forgot-password', [GuestAuthController::class, 'forgotPassword'])->name('forgot-password');

});

// Routes using auth
Route::group(['prefix' => 'v1', 'as' => 'api', 'middleware' => ['auth:sanctum']], function () {

    Route::post('logout', [LogoutController::class, 'logout'])->name('logout');
    Route::post('change-password', [UserController::class, 'changePassword'])->name('change-password');
    Route::put('update-profile', [UserController::class, 'updateProfile'])->name('update-profile');

    Route::post('post/store', [PostController::class, 'storePost'])->name('post.store');
    Route::post('post/toggle-like', [PostController::class, 'togglePostLike'])->name('post.toggle-like');
    Route::post('post/comment/store', [PostController::class, 'postComment'])->name('post.comment.store');

    Route::post('/friend-request', [FriendAndFollowerController::class, 'sendFriendRequest'])->name('friend-request');
    Route::post('/respond-friend-request', [FriendAndFollowerController::class, 'respondToFriendRequest'])->name('respond-friend-request');
    Route::post('/user/toggle-follow', [FriendAndFollowerController::class, 'togglefollowUser'])->name('user.toggle-follow');
    Route::get('/user/followers', [FriendAndFollowerController::class, 'getAllFollowers'])->name('user.followers');
    Route::get('/user/followings', [FriendAndFollowerController::class, 'getAllFollowings'])->name('user.followings');
    Route::get('/user/friends', [FriendAndFollowerController::class, 'getUserFriends'])->name('user.friends');
    Route::get('/user/pending-friend-requests', [FriendAndFollowerController::class, 'getUserPendingFriendRequest'])->name('user.pending-friend-requests');
    Route::get('/user/get-friend-requests', [FriendAndFollowerController::class, 'getUserReceivedFriendRequest'])->name('user.get-friend-requests');
    
    Route::post('/get-user-list', [UserController::class, 'getUserList'])->name('get-user-list');
    Route::get('/get-trainers-and-owners', [UserController::class, 'getTrainersAndOwnersOnly'])->name('get-trainers-and-owners');
    Route::post('/assign-new-role', [UserController::class, 'assignNewRole'])->name('assign-new-role');

    Route::post('/all-horses/list', [HorseController::class, 'allHorses'])->name('all-horses.list');
    Route::post('/my-horses/list', [HorseController::class, 'myHorses'])->name('my-horses.list');
    Route::post('/horse/store', [HorseController::class, 'storeHorse'])->name('horse.store');
    Route::put('/horse/update', [HorseController::class, 'updateHorse'])->name('horse.update');
    Route::post('/horse/toggle-follow', [HorseController::class, 'toggleFollowHorse'])->name('horse.toggle-follow');
    Route::post('/horse/assign-trainer', [HorseController::class, 'assignTrainerToHorse'])->name('horse.assign-trainer');
    Route::post('/horse/trainer', [HorseController::class, 'getHorseTrainer'])->name('horse.trainer');
    Route::post('/user/horses-trained', [HorseController::class, 'getHorsesTrainedByUser'])->name('horse.horses-trained');
});