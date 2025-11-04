<?php

use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('posts')->group(function() {
    Route::get('/', [PostController::class, 'index']);
    Route::post('/', [PostController::class, 'store']);

    // POST /api/posts/{post}/comments – add a comment (body)
    Route::post('/{postId}/comments', [PostController::class, 'storeComments']);
});


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
