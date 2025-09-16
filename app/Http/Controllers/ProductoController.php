<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Mostrar listado de productos
     */
    public function index()
    {
        $productos = Producto::all();
        return view('productos.index', compact('productos'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('productos.create');
    }

    /**
     * Guardar un nuevo producto
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:120',
            'tipo'   => 'required|string|in:empanada,papa_rellena',
            'tamaño' => 'required|string|max:50',
            'precio' => 'required|numeric|min:0',
        ]);

        Producto::create($request->all());

        return redirect()->route('productos.index')
            ->with('ok', 'Producto creado con éxito.');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Producto $producto)
    {
        return view('productos.edit', compact('producto'));
    }

    /**
     * Actualizar un producto
     */
    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => 'required|string|max:120',
            'tipo'   => 'required|string|in:empanada,papa_rellena',
            'tamaño' => 'required|string|max:50',
            'precio' => 'required|numeric|min:0',
        ]);

        $producto->update($request->all());

        return redirect()->route('productos.index')
            ->with('ok', 'Producto actualizado con éxito.');
    }

    /**
     * Eliminar un producto (si no tiene ventas)
     */
    public function destroy(Producto $producto)
    {
        if ($producto->tieneVentas()) {
            return back()->with('error', 'No se puede eliminar un producto con ventas registradas.');
        }

        $producto->delete();

        return redirect()->route('productos.index')
            ->with('ok', 'Producto eliminado con éxito.');
    }
}
