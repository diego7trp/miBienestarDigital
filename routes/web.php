<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RutinaController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\PerfilController;
use App\HttpVControllers\adminController;


Route::get('/admin/panel', [AdminController::class, 'admin_panel']);
Route::get('/admin/usuario', [AdminController::class, 'admin_usuario']);
Route::get('/admin/gestion', [AdminController::class, 'admin_gestionUsuario']);
Route::get('/admin/administrador', [AdminController::class, 'administrador']);


/*
|--------------------------------------------------------------------------
| Rutas públicas
|--------------------------------------------------------------------------
*/


// Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

// Registro
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Redirección raíz
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});


/*
|--------------------------------------------------------------------------
| Rutas protegidas (requieren login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard (solo uno definido)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rutinas
    Route::resource('rutinas', RutinaController::class);
    Route::post('/rutinas/{rutina}/completar', [RutinaController::class, 'marcarCompletada'])
         ->name('rutinas.completar');

    // Tareas
    Route::resource('tareas', TareaController::class);
    Route::post('/tareas/{tarea}/completar', [TareaController::class, 'completar'])->name('tareas.completar');
    Route::get('/tareas-calendario', [TareaController::class, 'calendario'])->name('tareas.calendario');
    Route::get('/tareas-vencidas', [TareaController::class, 'vencidas'])->name('tareas.vencidas');

    // Perfil
    Route::get('/perfil', [PerfilController::class, 'show'])->name('perfil.show');
    Route::get('/perfil/editar', [PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [PerfilController::class, 'update'])->name('perfil.update');
});
