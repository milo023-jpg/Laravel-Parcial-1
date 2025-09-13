<?php

use Illuminate\Support\Facades\Route;

/* Route::get('/', function () {
    return view('welcome');
});
 */

// CRUD

use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ClienteController;

// Rutas principales
Route::get('/', function () {
    return view('menu');
})->name('menu');

Route::get('/menu', function () {
    return view('menu');
})->name('menu');


// Rutas para las vistas Blade de Productos (productos)
Route::get('/productos', [ProductoController::class, 'vistaIndex'])->name('productos.index');
Route::get('/productos/create', [ProductoController::class, 'create'])->name('productos.create');
Route::post('/productos', [ProductoController::class, 'storeWeb'])->name('productos.store');
Route::get('/productos/{id}/edit', [ProductoController::class, 'edit'])->name('productos.edit');
Route::put('/productos/{id}', [ProductoController::class, 'updateWeb'])->name('productos.update');
Route::delete('/productos/{id}', [ProductoController::class, 'destroyWeb'])->name('productos.destroy');

// Rutas para las vistas Blade de Clientes (clientes)
Route::get('/clientes', [ClienteController::class, 'vistaIndex'])->name('clientes.index');
Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
Route::post('/clientes', [ClienteController::class, 'storeWeb'])->name('clientes.store');
Route::get('/clientes/{id}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
Route::put('/clientes/{id}', [ClienteController::class, 'updateWeb'])->name('clientes.update');
Route::delete('/clientes/{id}', [ClienteController::class, 'destroyWeb'])->name('clientes.destroy');


// Rutas para las vistas Blade de Pedidos (pedidos)
Route::get('/pedidos', [PedidoController::class, 'vistaIndex'])->name('pedidos.index');
Route::get('/pedidos/create', [PedidoController::class, 'create'])->name('pedidos.create');
Route::post('/pedidos', [PedidoController::class, 'storeWeb'])->name('pedidos.store');

