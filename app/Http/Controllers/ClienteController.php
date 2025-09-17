<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Mostrar listado de clientes.
     */
    public function index()
    {
        $clientes = Cliente::all();
        return view('clientes.index', compact('clientes'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Guardar un nuevo cliente.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'           => 'required|string|max:100',
            'tipo_documento'   => 'required|in:CC,TI,CE,NIT,PASAPORTE',
            'numero_documento' => 'required|string|max:50|unique:clientes,numero_documento',
            'telefono'         => 'nullable|string|max:20',
            'direccion'        => 'nullable|string|max:255',
            'ciudad'           => 'nullable|string|max:100',
            'frecuente'        => 'boolean',
        ]);

        $cliente = Cliente::create($request->all());

        // Si es una petición AJAX, devolver JSON
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cliente creado correctamente.',
                'cliente' => $cliente
            ]);
        }

        return redirect()->route('admin.clientes.index')
                         ->with('success', 'Cliente creado correctamente.');
    }

    /**
     * Mostrar un cliente en detalle.
     */
    public function show(Cliente $cliente)
    {
        return view('clientes.show', compact('cliente'));
    }

    /**
     * Formulario para editar un cliente.
     */
    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Actualizar un cliente.
     */
    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'nombre'           => 'required|string|max:100',
            'tipo_documento'   => 'required|in:CC,TI,CE,NIT,PASAPORTE',
            'numero_documento' => 'required|string|max:50|unique:clientes,numero_documento,' . $cliente->id,
            'telefono'         => 'nullable|string|max:20',
            'direccion'        => 'nullable|string|max:255',
            'ciudad'           => 'nullable|string|max:100',
            'frecuente'        => 'boolean',
        ]);

        $cliente->update($request->all());

        return redirect()->route('admin.clientes.index')
                         ->with('success', 'Cliente actualizado correctamente.');
    }

    /**
     * Eliminar un cliente.
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return redirect()->route('admin.clientes.index')
                         ->with('success', 'Cliente eliminado correctamente.');
    }
}
