<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\AuthController; 

// 🟢 RUTAS PÚBLICAS (Agrega las de Login y Registro aquí arriba)
Route::post('login', [AuthController::class, 'login']);       // 🔥 FALTA ESTA LÍNEA
Route::post('register', [AuthController::class, 'register']); // 🔥 FALTA ESTA LÍNEA

Route::get('productos', [ProductoController::class, 'index']);
Route::get('categorias', [CategoriaController::class, 'index']);
Route::get('productos/{producto}', [ProductoController::class, 'show']);

// 🔒 RUTAS PROTEGIDAS (Solo para usuarios autenticados)
Route::group(['middleware' => ['auth']], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
    
    // Acciones protegidas por Sanctum y tus Policies
    Route::post('productos', [ProductoController::class, 'store']);
    Route::put('productos/{producto}', [ProductoController::class, 'update']);
    Route::delete('productos/{producto}', [ProductoController::class, 'destroy']);
});