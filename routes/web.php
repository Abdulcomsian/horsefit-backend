<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;


// Clear cache using reoptimized class
Route::get('/optimize-clear', function() {
    Artisan::call('optimize:clear');
    return 'View cache cleared';
});

//Clear Permissions cache
Route::get('/permission-clear', function() {
    Artisan::call('cache:forget spatie.permission.cache');
    Artisan::call('optimize:clear');
    return 'View cache cleared';
});

Route::get('/', function () {
    return view('welcome');
})->name('index');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
->name('home')->middleware('auth');

Route::get('migrate', function() {
    Artisan::call('migrate');
    // Artisan::call('migrate --path=database/migrations/path_to_file.php');
    die('Migrated');
});

require __DIR__.'/admin.php';

Route::fallback(function () {
    return redirect()->route('index');
});
