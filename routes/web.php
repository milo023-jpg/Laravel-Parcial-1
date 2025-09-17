<?php

use Illuminate\Support\Facades\Route;

/* Route::get('/', function () {
    return view('welcome');
});
 */

// CRUD

//use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VentaController;

// Rutas principales
Route::get('/', function () {
    return view('menu');
})->name('menu');

Route::get('/menu', function () {
    return view('menu');
})->name('menu');


// Rutas para las vistas Blade de Productos (productos)
//Route::get('/productos', [ProductoController::class, 'vistaIndex'])->name('productos.index');
//Route::get('/productos/create', [ProductoController::class, 'create'])->name('productos.create');
//Route::post('/productos', [ProductoController::class, 'storeWeb'])->name('productos.store');
//Route::get('/productos/{id}/edit', [ProductoController::class, 'edit'])->name('productos.edit');
//Route::put('/productos/{id}', [ProductoController::class, 'updateWeb'])->name('productos.update');
//Route::delete('/productos/{id}', [ProductoController::class, 'destroyWeb'])->name('productos.destroy');

Route::prefix('admin')->name('admin.')->group(function () {
// Gestión de Clientes
Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
Route::get('/clientes/{cliente}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
Route::put('/clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update');
Route::delete('/clientes/{cliente}', [ClienteController::class, 'destroy'])->name('clientes.destroy');

// Gestión de Ventas
Route::get('/ventas', [VentaController::class, 'vistaIndex'])->name('ventas.index');
Route::get('/ventas/create', [VentaController::class, 'create'])->name('ventas.create');
Route::post('/ventas', [VentaController::class, 'storeWeb'])->name('ventas.store');
Route::get('/ventas/{venta}', [VentaController::class, 'vistaShow'])->name('ventas.show');
Route::get('/ventas/{venta}/edit', [VentaController::class, 'edit'])->name('ventas.edit');
Route::put('/ventas/{venta}', [VentaController::class, 'update'])->name('ventas.update');
Route::delete('/ventas/{venta}', [VentaController::class, 'destroy'])->name('ventas.destroy');

});


// Rutas para las vistas Blade de Punto de Venta (POS)
Route::get('/pos', [VentaController::class, 'create'])->name('pos');
Route::post('/pos/venta', [VentaController::class, 'storeWeb']);
Route::get('/pos/productos/buscar', [VentaController::class, 'buscarProductos']);
Route::get('/pos/clientes', [VentaController::class, 'obtenerClientes']);
Route::post('/pos/clientes', [VentaController::class, 'crearCliente']);
Route::get('/pos/productos/puede-eliminar/{id}', [VentaController::class, 'puedeEliminarProducto']);


