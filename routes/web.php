<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\ActividadController;
use App\Http\Controllers\ReminderController;

/*
|--------------------------------------------------------------------------
| Rutas de Autenticación y Home
|--------------------------------------------------------------------------
*/

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Rutas de Posts y Comentarios
|--------------------------------------------------------------------------
*/

Route::resource('posts', PostController::class);

Route::middleware(['auth'])->group(function () {
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

/*
|--------------------------------------------------------------------------
| RUTAS DE NOTAS (LAS QUE NECESITAS PARA EL LAB)
|--------------------------------------------------------------------------
*/

Route::get('/notas', [NotaController::class, 'index'])->name('notas.index');
Route::post('/notas', [NotaController::class, 'store'])->name('notas.store');
Route::delete('/notas/{nota}', [NotaController::class, 'destroy'])->name('notas.destroy');

/*
|--------------------------------------------------------------------------
| Rutas de Actividades para Notas
|--------------------------------------------------------------------------
*/
Route::prefix('notas/{nota}')->group(function () {
    Route::post('actividads', [ActividadController::class, 'store'])->name('actividads.store');
    Route::patch('actividads/{actividad}', [ActividadController::class, 'update'])->name('actividads.update');
});

/*
|--------------------------------------------------------------------------
| Rutas de Recordatorios
|--------------------------------------------------------------------------
*/

Route::get('/reminders/create', [ReminderController::class, 'create'])->name('reminders.create'); 
Route::post('/reminders', [ReminderController::class, 'store'])->name('reminders.store'); 
Route::get('/reminders', [ReminderController::class, 'index'])->name('reminders.index');
