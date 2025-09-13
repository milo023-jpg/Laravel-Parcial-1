<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'productos';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nombre',
        'tipo_relleno',
        'tamaño',
        'precio'
    ];

    // Relación: Un producto puede tener muchas ventas
    public function ventas()
    {
        return $this->hasMany(\App\Models\Venta::class, 'id_producto', 'id');
    }
}