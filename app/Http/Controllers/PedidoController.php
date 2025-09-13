<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Cliente;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    // ================= API (JSON) =================

    // Muestra todos los registros de pedidos
    public function index()
    {
        $pedidos = Pedido::with(['producto', 'cliente'])->orderBy('fecha_pedido', 'desc')->get();
        return response()->json($pedidos);
    }

    // Crea un nuevo registro de pedido
    public function store(Request $request)
    {
        $pedido = Pedido::create($request->all());
        return response()->json($pedido, 201);
    }

    // Muestra un registro por id
    public function show($id)
    {
        $pedido = Pedido::with(['producto', 'cliente'])->findOrFail($id);
        return response()->json($pedido);
    }

    // ================= VISTAS (Blade) =================

    // Muestra la vista con todos los pedidos
    public function vistaIndex()
    {
        $pedidos = Pedido::with(['producto', 'cliente'])->orderBy('fecha_pedido', 'desc')->get();
        return view('pedidos.index', compact('pedidos'));
    }

    // Muestra el formulario de creación
    public function create()
    {
        $productos = Producto::all();
        $clientes = Cliente::all();
        return view('pedidos.create', compact('productos', 'clientes'));
    }

    // Guarda el pedido desde el formulario web
    public function storeWeb(Request $request)
    {
        // Buscar el producto y cliente
        $producto = Producto::findOrFail($request->id_producto);
        $cliente = Cliente::findOrFail($request->id_cliente);
        
        // Calcular el valor total del pedido
        $valor = $request->cantidad * $producto->precio;
        
        // Aplicar descuento si el cliente es frecuente
        $descuento = 0.00;
        if ($cliente->frecuente && $request->descuento > 0) {
            $descuento = $request->descuento;
        }
        
        // Calcular valor total con descuento
        $valor_total = $valor - ($valor * ($descuento / 100));
        
        // Crear el pedido
        Pedido::create([
            'id_producto' => $request->id_producto,
            'id_cliente' => $request->id_cliente,
            'cantidad' => $request->cantidad,
            'precio_unitario' => $precio_unitario = $producto->precio,
            'subtotal' => $subtotal,
            'descuento' => $descuento,
            'valor_total' => $valor_total,
            'fecha_pedido' => now()
        ]);

        
        return redirect()->route('pedidos.index')->with('success', 'Pedido registrado correctamente');
    }
}