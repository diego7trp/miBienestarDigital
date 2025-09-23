<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\View; // Importamos la clase View para usar View::make()

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rutas de Vistas
Route::get('/', function () {
    return View::make('login');
})->name('login');

Route::get('/admin_panel', function () {
    return View::make('admin_panel');
})->name('admin.dashboard');

// Ruta para la tabla de usuarios
Route::get('/usuarios', function () {
    return View::make('admin_usuario');
})->name('usuarios.gestion');

// Rutas para las vistas de especialista y paciente
Route::get('/especialista', function () {
    return View::make('especialista_panel');
})->name('especialista.dashboard');

Route::get('/paciente', function () {
    return View::make('paciente_panel');
})->name('paciente.dashboard');

// Rutas para procesar formularios
Route::post('/login', [AuthController::class, 'login'])->name('login.post');