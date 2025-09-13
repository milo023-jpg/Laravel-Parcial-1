<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pedido extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'pedidos';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_producto',  
        'id_cliente',  
        'cantidad',
        'precio_unitario',
        'subtotal',
        'descuento',
        'valor_total',
        'fecha_pedido'
    ];

    public function producto()
    {
        return $this->belongsTo(\App\Models\Producto::class, 'id_producto', 'id');
    }
    
    public function cliente()
    {
        return $this->belongsTo(\App\Models\Cliente::class, 'id_cliente', 'id');
    }
}