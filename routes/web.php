<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\ActividadController;

/*
|--------------------------------------------------------------------------
| Rutas de Autenticación y Home
|--------------------------------------------------------------------------
*/

// Rutas de autenticación (Login, Register, etc.)
Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Rutas de Posts y Comentarios (Requieren autenticación)
|--------------------------------------------------------------------------
*/

Route::resource('posts', PostController::class);

Route::middleware(['auth'])->group(function () {
    // CRUD de Comentarios, anidados en Posts
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});


/*
|--------------------------------------------------------------------------
| Rutas de Notas y Actividades (Funcionalidad principal del Laboratorio)
|--------------------------------------------------------------------------
*/

// RUTAS CRUD Notas
Route::get('/notas', [NotaController::class, 'index'])->name('notas.index');
Route::post('/notas', [NotaController::class, 'store'])->name('notas.store');
Route::delete('/notas/{nota}', [NotaController::class, 'destroy'])->name('notas.destroy'); // Borrado en cascada

// RUTAS CRUD Actividades (Anidadas a Notas)
Route::prefix('notas/{nota}')->group(function () {
    Route::post('actividads', [ActividadController::class, 'store'])->name('actividads.store');
    Route::patch('actividads/{actividad}', [ActividadController::class, 'update'])->name('actividads.update');
});