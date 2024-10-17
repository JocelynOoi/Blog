<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;


// Home route (optional, redirects to posts index)
Route::get('/', function () {
    return redirect()->route('posts.index');
});

// Resourceful route for posts
Route::resource('posts', PostController::class);

use App\Http\Controllers\ProfileControlle;