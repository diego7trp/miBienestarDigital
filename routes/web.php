<?php
use App\Http\Controllers\InicioController;

Route::get('/inicio', [InicioController::class, 'index']);

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
