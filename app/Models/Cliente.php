<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cliente extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'clientes';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nombre',
        'telefono',
        'direccion',
        'frecuente'
    ];

    // Relación: Un producto puede tener muchas ventas
    public function ventas()
    {
        return $this->hasMany(\App\Models\Venta::class, 'id_cliente', 'id');
    }
}