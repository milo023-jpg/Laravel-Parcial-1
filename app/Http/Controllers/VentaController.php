<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\VentaItem;
use App\Models\Producto;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    // ================= API (JSON) =================

    // Muestra todas las ventas
    public function index()
    {
        $ventas = Venta::with(['cliente', 'items.producto'])
            ->orderBy('fecha_venta', 'desc')
            ->get();
        return response()->json($ventas);
    }

    // Crea una nueva venta
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Crear la venta
            $venta = Venta::create([
                'cliente_id' => $request->cliente_id,
                'descuento' => $request->descuento ?? 0,
                'valor_total' => $request->valor_total,
                'fecha_venta' => now()
            ]);

            // Crear los ítems de la venta
            foreach ($request->items as $item) {
                VentaItem::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $item['producto_id'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'subtotal' => $item['subtotal']
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'venta' => $venta->load('items.producto', 'cliente')
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la venta: ' . $e->getMessage()
            ], 500);
        }
    }

    // Muestra una venta específica
    public function show($id)
    {
        $venta = Venta::with(['cliente', 'items.producto'])->findOrFail($id);
        return response()->json($venta);
    }

    // Buscar productos para el autocompletado
    public function buscarProductos(Request $request)
    {
        $term = $request->get('q', '');
        
        $productos = Producto::where('nombre', 'LIKE', "%{$term}%")
            ->select('id', 'nombre', 'tipo', 'tamaño', 'precio')
            ->limit(10)
            ->get();

        return response()->json($productos);
    }

    // Obtener todos los productos
    public function obtenerProductos()
    {
        $productos = Producto::select('id', 'nombre', 'tipo', 'tamaño', 'precio')
            ->orderBy('nombre')
            ->get();
        
        return response()->json($productos);
    }

    // Obtener todos los clientes
    public function obtenerClientes()
    {
        $clientes = Cliente::select('id', 'tipo_documento', 'numero_documento', 'nombre', 'direccion', 'ciudad', 'telefono')
            ->orderBy('nombre')
            ->get();
        
        return response()->json($clientes);
    }

    // Crear nuevo cliente
    public function crearCliente(Request $request)
    {
        $request->validate([
            'tipo_documento' => 'required|string|in:CC,TI,CE,PP',
            'numero_documento' => 'required|string|unique:clientes,numero_documento',
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'ciudad' => 'required|string|max:100',
            'telefono' => 'required|string|max:20'
        ]);

        $cliente = Cliente::create([
            'tipo_documento' => $request->tipo_documento,
            'numero_documento' => $request->numero_documento,
            'nombre' => $request->nombre,
            'direccion' => $request->direccion,
            'ciudad' => $request->ciudad,
            'telefono' => $request->telefono
        ]);

        return response()->json([
            'success' => true,
            'cliente' => $cliente
        ], 201);
    }

    // ================= VISTAS (Blade) =================

    // Muestra la vista con todas las ventas
    public function vistaIndex()
    {
        $ventas = Venta::with(['cliente', 'items.producto'])
            ->orderBy('fecha_venta', 'desc')
            ->get();
        return view('ventas.index', compact('ventas'));
    }

    // Muestra el formulario de creación
    public function create()
    {
        $productos = Producto::select('id', 'nombre', 'tipo', 'tamaño', 'precio')
            ->orderBy('nombre')
            ->get();
        
        $clientes = Cliente::select('id', 'tipo_documento', 'numero_documento', 'nombre', 'direccion', 'ciudad', 'telefono')
            ->orderBy('nombre')
            ->get();

        return view('ventas.create', compact('productos', 'clientes'));
    }

    // Guarda la venta desde el formulario web
    public function storeWeb(Request $request)
    {
        $request->validate([
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'descuento' => 'nullable|numeric|min:0',
            'pago' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            // Calcular totales
            $subtotal = 0;
            $items = [];

            foreach ($request->productos as $productoData) {
                $producto = Producto::findOrFail($productoData['id']);
                
                $itemSubtotal = $producto->precio * $productoData['cantidad'];
                $subtotal += $itemSubtotal;

                $items[] = [
                    'producto_id' => $producto->id,
                    'cantidad' => $productoData['cantidad'],
                    'precio_unitario' => $producto->precio,
                    'subtotal' => $itemSubtotal
                ];
            }

            // Aplicar descuento
            $descuento = $request->descuento ?? 0;
            $valorTotal = $subtotal - $descuento;

            // Verificar que el pago sea suficiente
            if ($request->pago < $valorTotal) {
                throw new \Exception("El pago debe ser mayor o igual al total de la venta");
            }

            // Crear la venta
            $venta = Venta::create([
                'cliente_id' => $request->cliente_id ?: null,
                'descuento' => $descuento,
                'valor_total' => $valorTotal,
                'fecha_venta' => now()
            ]);

            // Crear los ítems
            foreach ($items as $itemData) {
                $itemData['venta_id'] = $venta->id;
                VentaItem::create($itemData);
            }

            DB::commit();

            return redirect()->route('ventas.index')
                ->with('success', "Venta #{$venta->id} registrada correctamente. Cambio: $" . number_format($request->pago - $valorTotal, 2));

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', $e->getMessage());
        }
    }

    // Mostrar una venta específica
    public function vistaShow($id)
    {
        $venta = Venta::with(['cliente', 'items.producto'])->findOrFail($id);
        return view('ventas.show', compact('venta'));
    }

    // ================= UTILIDADES =================

    // Verificar si un producto puede ser eliminado
    public function puedeEliminarProducto($productoId)
    {
        $tieneVentas = VentaItem::where('producto_id', $productoId)->exists();
        
        return response()->json([
            'puede_eliminar' => !$tieneVentas,
            'mensaje' => $tieneVentas ? 'No se puede eliminar el producto porque tiene ventas registradas' : 'El producto puede ser eliminado'
        ]);
    }

    // Generar número de factura
    private function generarNumeroFactura()
    {
        $ultimaVenta = Venta::orderBy('id', 'desc')->first();
        $siguienteNumero = $ultimaVenta ? $ultimaVenta->id + 1 : 1;
        return str_pad($siguienteNumero, 6, '0', STR_PAD_LEFT);
    }

    // Calcular cambio
    public function calcularCambio(Request $request)
    {
        $total = $request->get('total', 0);
        $pago = $request->get('pago', 0);
        $cambio = $pago - $total;

        return response()->json([
            'cambio' => $cambio >= 0 ? $cambio : 0,
            'pago_suficiente' => $cambio >= 0
        ]);
    }
}