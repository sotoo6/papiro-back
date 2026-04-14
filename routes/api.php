<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\DireccionController;
use App\Http\Controllers\Api\CarritoController;
use App\Http\Controllers\Api\PedidoController;
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

// -------------------------
// Autenticación pública
// -------------------------

// Registrar un nuevo usuario cliente
Route::post('/register', [AuthController::class, 'register']);

// Iniciar sesión
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Rutas protegidas
|--------------------------------------------------------------------------
|
| Estas rutas requieren autenticación mediante Sanctum.
|
*/
Route::middleware('auth:sanctum')->group(function () {
    // Devuelve el usuario autenticado.
    Route::get('/me', [AuthController::class, 'me']);

    // Cierra la sesión del usuario autenticado.
    Route::post('/logout', [AuthController::class, 'logout']);



    // Lista todas las direcciones del usuario autenticado.
    Route::get('/direcciones', [DireccionController::class, 'index']);

    // Crea una nueva dirección para el usuario autenticado.
    Route::post('/direcciones', [DireccionController::class, 'store']);

    // Muestra una dirección concreta del usuario autenticado.
    Route::get('/direcciones/{id}', [DireccionController::class, 'show']);

    // Actualiza una dirección concreta del usuario autenticado.
    Route::put('/direcciones/{id}', [DireccionController::class, 'update']);

    // Elimina una dirección concreta del usuario autenticado.
    Route::delete('/direcciones/{id}', [DireccionController::class, 'destroy']);

    // Marca una dirección como principal para el usuario autenticado.
    Route::patch('/direcciones/{id}/principal', [DireccionController::class, 'setPrincipal']);



    // Devuelve el carrito del usuario autenticado.
    Route::get('/carrito', [CarritoController::class, 'index']);

    // Añade un producto al carrito del usuario autenticado.
    Route::post('/carrito/items', [CarritoController::class, 'store']);

    // Actualiza la cantidad de una línea del carrito.
    Route::put('/carrito/items/{id}', [CarritoController::class, 'update']);

    // Elimina una línea del carrito.
    Route::delete('/carrito/items/{id}', [CarritoController::class, 'destroy']);

    // Vacía completamente el carrito.
    Route::delete('/carrito/vaciar', [CarritoController::class, 'clear']);


    // Crea un pedido a partir del carrito del usuario autenticado.
    Route::post('/pedidos', [PedidoController::class, 'store']);

    // Devuelve todos los pedidos del usuario autenticado.
    Route::get('/pedidos', [PedidoController::class, 'index']);

    // Devuelve un pedido concreto del usuario autenticado.
    Route::get('/pedidos/{id}', [PedidoController::class, 'show']);
});
