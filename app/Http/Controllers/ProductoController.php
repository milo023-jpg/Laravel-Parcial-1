<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    // ================= API (JSON) =================

    // Muestra todos los registros de productos
    public function index()
    {
        $productos = Producto::all();
        return response()->json($productos);
    }

    // Crea un nuevo registro de producto
    public function store(Request $request)
    {
        $producto = Producto::create($request->all());
        return response()->json($producto, 201);
    }

    // Muestra un registro por id
    public function show($id)
    {
        $producto = Producto::findOrFail($id);
        return response()->json($producto);
    }

    // Actualiza un registro
    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);
        $producto->update($request->all());
        return response()->json($producto);
    }

    // Elimina un registro
    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->delete();
        return response()->json(null, 204);
    }


    // ================= VISTAS (Blade) =================

    // Muestra la vista con todos los productos
    public function vistaIndex()
    {
        $productos = Producto::all();
        return view('productos.index', compact('productos'));
    }

    // Muestra el formulario de creación
    public function create()
    {
        return view('productos.create');
    }

    // Guarda el producto desde el formulario web
    public function storeWeb(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:100',
            'tipo_relleno' => 'nullable|max:50',
            'tamaño' => 'required|in:Pequeño,Mediano,Grande',
            'precio' => 'required|numeric|min:0'
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.',
            'tipo_relleno.max' => 'El tipo de relleno no puede tener más de 50 caracteres.',
            'tamaño.required' => 'El tamaño es obligatorio.',
            'tamaño.in' => 'El tamaño debe ser Pequeño, Mediano o Grande.',
            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un número válido.',
            'precio.min' => 'El precio debe ser mayor o igual a 0.'
        ]);

        Producto::create($request->all());
        return redirect()->route('productos.index')->with('success', 'Producto creado correctamente');
    }

    // Muestra el formulario de edición
    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        return view('productos.edit', compact('producto'));
    }

    // Actualiza el producto desde el formulario web
    public function updateWeb(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|max:100',
            'tipo_relleno' => 'nullable|max:50',
            'tamaño' => 'required|in:Pequeño,Mediano,Grande',
            'precio' => 'required|numeric|min:0'
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres.',
            'tipo_relleno.max' => 'El tipo de relleno no puede tener más de 50 caracteres.',
            'tamaño.required' => 'El tamaño es obligatorio.',
            'tamaño.in' => 'El tamaño debe ser Pequeño, Mediano o Grande.',
            'precio.required' => 'El precio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un número válido.',
            'precio.min' => 'El precio debe ser mayor o igual a 0.'
        ]);

        $producto = Producto::findOrFail($id);
        $producto->update($request->all());
        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente');
    }

    // Elimina el producto desde la vista web
    public function destroyWeb($id)
    {
        $producto = Producto::findOrFail($id);
        
        // Verificar si el producto tiene pedidos registrados
        if ($producto->pedidos()->exists()) {
            return redirect()->route('productos.index')
                ->with('error', 'No se puede eliminar el producto porque ya tiene pedidos registrados');
        }
        
        $producto->delete();
        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado correctamente');
    }
}