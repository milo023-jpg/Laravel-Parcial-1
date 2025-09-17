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

                // Reducir el stock del producto
                $producto = Producto::find($item['producto_id']);
                if ($producto) {
                    $producto->stock -= $item['cantidad'];
                    $producto->save();
                }
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
        
        $productos = Producto::where(function($query) use ($term) {
                $query->where('codigo', 'LIKE', "%{$term}%")
                      ->orWhere('nombre', 'LIKE', "%{$term}%");
            })
            ->where('stock', '>', 0) // Solo productos con stock
            ->select('id', 'codigo', 'nombre', 'precio', 'stock')
            ->limit(10)
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
        $productos = Producto::where('stock', '>', 0)
            ->select('id', 'codigo', 'nombre', 'precio', 'stock')
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
            'cliente_id' => 'required|exists:clientes,id',
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
                
                // Verificar stock disponible
                if ($producto->stock < $productoData['cantidad']) {
                    throw new \Exception("Stock insuficiente para {$producto->nombre}. Disponible: {$producto->stock}");
                }

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
                'cliente_id' => $request->cliente_id,
                'descuento' => $descuento,
                'valor_total' => $valorTotal,
                'fecha_venta' => now()
            ]);

            // Crear los ítems y actualizar stock
            foreach ($items as $itemData) {
                $itemData['venta_id'] = $venta->id;
                VentaItem::create($itemData);

                // Actualizar stock
                $producto = Producto::find($itemData['producto_id']);
                $producto->stock -= $itemData['cantidad'];
                $producto->save();
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

    // ================= REPORTES =================

    // Reporte de ventas por fecha
    public function reporteVentas(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', now()->startOfMonth());
        $fechaFin = $request->get('fecha_fin', now()->endOfMonth());

        $ventas = Venta::with(['cliente', 'items.producto'])
            ->whereBetween('fecha_venta', [$fechaInicio, $fechaFin])
            ->orderBy('fecha_venta', 'desc')
            ->get();

        $totalVentas = $ventas->sum('valor_total');
        $totalDescuentos = $ventas->sum('descuento');
        $cantidadVentas = $ventas->count();

        return response()->json([
            'ventas' => $ventas,
            'resumen' => [
                'total_ventas' => $totalVentas,
                'total_descuentos' => $totalDescuentos,
                'cantidad_ventas' => $cantidadVentas,
                'promedio_venta' => $cantidadVentas > 0 ? $totalVentas / $cantidadVentas : 0
            ]
        ]);
    }

    // Productos más vendidos
    public function productosMasVendidos(Request $request)
    {
        $limite = $request->get('limite', 10);
        
        $productos = VentaItem::select('producto_id', DB::raw('SUM(cantidad) as total_vendido'), DB::raw('SUM(subtotal) as ingresos'))
            ->with('producto')
            ->groupBy('producto_id')
            ->orderBy('total_vendido', 'desc')
            ->limit($limite)
            ->get();

        return response()->json($productos);
    }

    // Reporte de ventas por tipo de cliente (Mostrador vs Registrados)
    public function reporteTipoCliente(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', now()->startOfMonth());
        $fechaFin = $request->get('fecha_fin', now()->endOfMonth());

        $ventasQuery = Venta::whereBetween('fecha_venta', [$fechaInicio, $fechaFin]);
        
        $totalVentas = $ventasQuery->count();
        $ventasMostrador = $ventasQuery->whereNull('cliente_id')->count();
        $ventasRegistrados = $totalVentas - $ventasMostrador;
        
        $ingresosMostrador = Venta::whereBetween('fecha_venta', [$fechaInicio, $fechaFin])
            ->whereNull('cliente_id')
            ->sum('valor_total');
            
        $ingresosRegistrados = Venta::whereBetween('fecha_venta', [$fechaInicio, $fechaFin])
            ->whereNotNull('cliente_id')
            ->sum('valor_total');

        return response()->json([
            'total_ventas' => $totalVentas,
            'mostrador' => [
                'cantidad' => $ventasMostrador,
                'porcentaje' => $totalVentas > 0 ? round(($ventasMostrador / $totalVentas) * 100, 2) : 0,
                'ingresos' => $ingresosMostrador
            ],
            'registrados' => [
                'cantidad' => $ventasRegistrados,
                'porcentaje' => $totalVentas > 0 ? round(($ventasRegistrados / $totalVentas) * 100, 2) : 0,
                'ingresos' => $ingresosRegistrados
            ]
        ]);
    }

    // Reporte de ventas por ciudad
    public function reportePorCiudad(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', now()->startOfMonth());
        $fechaFin = $request->get('fecha_fin', now()->endOfMonth());

        $ventasPorCiudad = Venta::join('clientes', 'ventas.cliente_id', '=', 'clientes.id')
            ->whereBetween('fecha_venta', [$fechaInicio, $fechaFin])
            ->select('clientes.ciudad', DB::raw('COUNT(*) as cantidad_ventas'), DB::raw('SUM(valor_total) as total_ingresos'))
            ->groupBy('clientes.ciudad')
            ->orderBy('total_ingresos', 'desc')
            ->get();

        // Incluir ventas de mostrador
        $ventasMostrador = Venta::whereBetween('fecha_venta', [$fechaInicio, $fechaFin])
            ->whereNull('cliente_id')
            ->selectRaw("'Mostrador' as ciudad, COUNT(*) as cantidad_ventas, SUM(valor_total) as total_ingresos")
            ->first();

        if ($ventasMostrador && $ventasMostrador->cantidad_ventas > 0) {
            $ventasPorCiudad->prepend($ventasMostrador);
        }

        return response()->json($ventasPorCiudad);
    }

    // Reporte de ventas por tipo de producto
    public function reportePorTipoProducto(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', now()->startOfMonth());
        $fechaFin = $request->get('fecha_fin', now()->endOfMonth());

        $ventasPorTipo = VentaItem::join('venta_items', 'venta_items.producto_id', '=', 'productos.id')
            ->join('ventas', 'venta_items.venta_id', '=', 'ventas.id')
            ->join('productos', 'venta_items.producto_id', '=', 'productos.id')
            ->whereBetween('ventas.fecha_venta', [$fechaInicio, $fechaFin])
            ->select('productos.tipo', DB::raw('COUNT(*) as cantidad_vendida'), DB::raw('SUM(venta_items.subtotal) as ingresos'))
            ->groupBy('productos.tipo')
            ->orderBy('ingresos', 'desc')
            ->get();

        return response()->json($ventasPorTipo);
    }

    // Dashboard con métricas generales
    public function dashboard(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', now()->startOfMonth());
        $fechaFin = $request->get('fecha_fin', now()->endOfMonth());

        // Ventas totales
        $ventasHoy = Venta::whereDate('fecha_venta', now())->sum('valor_total');
        $ventasMes = Venta::whereBetween('fecha_venta', [$fechaInicio, $fechaFin])->sum('valor_total');
        
        // Productos más vendidos hoy
        $productosHoy = VentaItem::join('ventas', 'venta_items.venta_id', '=', 'ventas.id')
            ->join('productos', 'venta_items.producto_id', '=', 'productos.id')
            ->whereDate('ventas.fecha_venta', now())
            ->select('productos.nombre', DB::raw('SUM(venta_items.cantidad) as total'))
            ->groupBy('productos.id', 'productos.nombre')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get();

        // Clientes activos
        $clientesActivos = Venta::whereNotNull('cliente_id')
            ->whereBetween('fecha_venta', [$fechaInicio, $fechaFin])
            ->distinct('cliente_id')
            ->count();

        return response()->json([
            'ventas_hoy' => $ventasHoy,
            'ventas_mes' => $ventasMes,
            'productos_populares_hoy' => $productosHoy,
            'clientes_activos_mes' => $clientesActivos
        ]);
    }

    // ================= UTILIDADES =================

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