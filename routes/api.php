<?php
use App\Http\Controllers\CategoriaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductoController;

// Rutas de autenticación que ya tienes funcionando
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout']);
Route::get('me', [AuthController::class, 'me']);

// 🌟 RUTAS DE PRODUCTOS (Declaradas explícitamente fuera de cualquier restricción)
Route::get('productos', [ProductoController::class, 'index']);
Route::get('productos/{producto}', [ProductoController::class, 'show']);
Route::post('productos', [ProductoController::class, 'store']); 
Route::post('productos/{producto}', [ProductoController::class, 'update']);
Route::delete('productos/{producto}', [ProductoController::class, 'destroy']);

Route::apiResource('categorias', CategoriaController::class); //
Route::get('categorias/{categoria}/productos', [CategoriaController::class, 'productos']); //