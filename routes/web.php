<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotaController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/notas', [NotaController::class, 'index'])->name('notas.index');
Route::post('/notas', [NotaController::class, 'store'])->name('notas.store');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// RUTA PARA ELIMINAR LA NOTA (con borrado en cascada)
Route::delete('/notas/{nota}', [NotaController::class, 'destroy'])->name('notas.destroy');

// RUTAS ANIDADAS PARA EL CRUD DE ACTIVIDADES
Route::prefix('notas/{nota}')->group(function () {
    // Para guardar una nueva actividad
    Route::post('actividads', [ActividadController::class, 'store'])->name('actividads.store');
    
    // Para marcar como completada (toggle)
    Route::patch('actividads/{actividad}', [ActividadController::class, 'update'])->name('actividads.update');
    
});