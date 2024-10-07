<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('migrate', function() {
    Artisan::call('migrate');
    // Artisan::call('migrate --path=database/migrations/path_to_file.php');
    die('Migrated');
});


require __DIR__.'/admin.php';
require __DIR__.'/auth.php';
