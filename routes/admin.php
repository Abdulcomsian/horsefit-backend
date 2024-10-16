<?php

// use App\Http\Controllers\Admin\ModuleController;
// use App\Http\Controllers\Admin\RoleController;
// use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\HorseController;
use App\Http\Controllers\Admin\LikeController;
use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Auth\ChangePasswordController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    // Permissions
    Route::delete('permissions/destroy', [PermissionsController::class, 'massDestroy'])->name('permissions.massDestroy');
    Route::resource('permissions', PermissionsController::class);

    // Roles
    Route::delete('roles/destroy', [RolesController::class, 'massDestroy'])->name('roles.massDestroy');
    Route::resource('roles', RolesController::class);

    // Users
    Route::delete('users/destroy', [UsersController::class, 'massDestroy'])->name('users.massDestroy');
    Route::resource('users', UsersController::class);

    // Horse
    Route::delete('horses/destroy', [HorseController::class, 'massDestroy'])->name('horses.massDestroy');
    Route::post('horses/media', [HorseController::class, 'storeMedia'])->name('horses.storeMedia');
    Route::resource('horses', HorseController::class);

    // Post
    Route::delete('posts/destroy', [PostController::class, 'massDestroy'])->name('posts.massDestroy');
    Route::post('posts/media', [PostController::class, 'storeMedia'])->name('posts.storeMedia');
    Route::get('/posts/{post}/likes', [LikeController::class, 'index'])->name('posts.show.likes');
    Route::get('/posts/{post}/comments', [CommentController::class, 'index'])->name('posts.show.comments');
    Route::post('/posts/comment/store', [PostController::class, 'commentStore'])->name('posts.comment.store');
    Route::resource('posts', PostController::class);

    // Comment
    Route::delete('comments/destroy', [CommentController::class, 'massDestroy'])->name('comments.massDestroy');
    Route::resource('comments', CommentController::class);

    // Like
    Route::delete('likes/destroy', [LikeController::class, 'massDestroy'])->name('likes.massDestroy');
    Route::resource('likes', LikeController::class);

});

Route::group(['prefix' => 'profile', 'as' => 'profile.', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('/Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', [ChangePasswordController::class, 'edit'])->name('password.edit');
        Route::post('password', [ChangePasswordController::class, 'update'])->name('password.update');
        Route::post('profile', [ChangePasswordController::class, 'updateProfile'])->name('password.updateProfile');
        Route::post('profile/destroy', [ChangePasswordController::class, 'destroy'])->name('password.destroyProfile');
    }
});

