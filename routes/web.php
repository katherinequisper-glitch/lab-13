<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('posts', PostController::class);

Route::middleware(['auth'])->group(function () {
    // Almacenar nuevo comentario
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    
    // Actualizar comentario (Formulario de edición se manejaría en un modal o en otra vista)
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    
    // Eliminar comentario
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});