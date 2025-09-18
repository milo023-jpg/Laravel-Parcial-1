<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';

    protected $fillable = [
        'cliente_id',
        'descuento',
        'valor_total',
        'fecha_venta',
    ];

    /**
     * Relación: una venta pertenece a un cliente
     */
    public function cliente()
    {
        return $this->belongsTo(\App\Models\Cliente::class, 'cliente_id', 'id');
    }

    /**
     * Relación: una venta tiene muchos ítems
     */
    public function items()
    {
        return $this->hasMany(\App\Models\VentaItem::class, 'venta_id', 'id');
    }

    /**
     * Relación indirecta: productos que aparecen en esta venta
     */
    public function productos()
    {
        return $this->hasManyThrough(
            \App\Models\Producto::class,    // modelo destino
            \App\Models\VentaItem::class,   // tabla intermedia
            'venta_id',                     // FK en venta_items hacia ventas
            'id',                           // PK en productos
            'id',                           // PK en ventas
            'id_producto'                   // FK en venta_items hacia productos
        );
    }

    /**
     * Helper: saber si la venta tiene ítems
     */
    public function tieneItems()
    {
        return $this->items()->exists();
    }
}
