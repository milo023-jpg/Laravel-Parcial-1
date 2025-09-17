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
    /**
     * Muestra el listado de ventas
     */
    public function index()
    {
        $ventas = Venta::with(['cliente', 'items.producto'])
            ->orderBy('fecha_venta', 'desc')
            ->get();
        return view('ventas.index', compact('ventas'));
    }

    /**
     * Muestra el formulario de creación de venta
     */
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

    /**
     * Guarda una nueva venta
     */
    public function store(Request $request)
    {
        $request->validate([
            'productos' => 'required|array|min:1',
            'productos.*' => 'required|exists:productos,id',
            'cantidades' => 'required|array|min:1',
            'cantidades.*' => 'required|integer|min:1',
            'cliente_id' => 'nullable|exists:clientes,id',
            'descuento' => 'nullable|numeric|min:0',
            'pago' => 'required|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            // Calcular totales
            $subtotal = 0;
            $items = [];

            foreach ($request->productos as $index => $productoId) {
                if (!empty($productoId) && !empty($request->cantidades[$index])) {
                    $producto = Producto::findOrFail($productoId);
                    $cantidad = $request->cantidades[$index];
                    
                    $itemSubtotal = $producto->precio * $cantidad;
                    $subtotal += $itemSubtotal;

                    $items[] = [
                        'producto_id' => $producto->id,
                        'cantidad' => $cantidad,
                        'precio_unitario' => $producto->precio,
                        'subtotal' => $itemSubtotal
                    ];
                }
            }

            if (empty($items)) {
                throw new \Exception("Debe seleccionar al menos un producto válido");
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

            $cambio = $request->pago - $valorTotal;

            return redirect()->route('pos')
                ->with('success', "Venta registrada correctamente. Cambio: $" . number_format($cambio, 0, ',', '.'));
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Crear un nuevo cliente desde el POS
     */
    public function storeCliente(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'tipo_documento' => 'required|in:CC,TI,CE,NIT,PASAPORTE',
            'numero_documento' => 'required|string|max:50|unique:clientes,numero_documento',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:100',
            'frecuente' => 'nullable|boolean'
        ]);

        try {
            $cliente = Cliente::create([
                'nombre' => $request->nombre,
                'tipo_documento' => $request->tipo_documento,
                'numero_documento' => $request->numero_documento,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
                'ciudad' => $request->ciudad,
                'frecuente' => $request->frecuente ?? false
            ]);

            return redirect()->route('pos')
                ->with('success', 'Cliente creado correctamente')
                ->with('nuevo_cliente_id', $cliente->id);
                
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al crear el cliente: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar una venta específica
     */
    public function show($id)
    {
        $venta = Venta::with(['cliente', 'items.producto'])->findOrFail($id);
        return view('ventas.show', compact('venta'));
    }
}