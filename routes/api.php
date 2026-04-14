<?php

use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\ProductoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas públicas del catálogo
|--------------------------------------------------------------------------
|
| Estas rutas no requieren autenticación.
| Sirven para consultar productos y categorías.
|
*/

// -------------------------
// Productos
// -------------------------

// Obtener todos los productos
Route::get('/productos', [ProductoController::class, 'index']);

// Obtener el detalle de un producto concreto
Route::get('/productos/{id}', [ProductoController::class, 'show']);

// -------------------------
// Categorías
// -------------------------

// Obtener todas las categorías
Route::get('/categorias', [CategoriaController::class, 'index']);

// Obtener una categoría concreta
Route::get('/categorias/{id}', [CategoriaController::class, 'show']);
