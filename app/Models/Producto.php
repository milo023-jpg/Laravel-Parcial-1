<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'nombre',
        'tipo',     // empanada o papa_rellena
        'tamaño',
        'precio',
    ];

    // Relación: un producto aparece en muchos ítems de venta
    public function items()
    {
        return $this->hasMany(\App\Models\VentaItem::class, 'id_producto', 'id');
    }

    // Relación indirecta: ventas donde aparece este producto
    public function ventas()
    {
        return $this->hasManyThrough(
            \App\Models\Venta::class,       // modelo destino
            \App\Models\VentaItem::class,   // tabla intermedia
            'id_producto',                  // FK en venta_items
            'id',                           // PK en ventas
            'id',                           // PK en productos
            'id_venta'                      // FK en venta_items hacia ventas

            
        );
    }

    // Helper: saber si tiene ventas
    public function tieneVentas()
    {
        return $this->items()->exists();
}
}