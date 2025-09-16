<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Venta; // Ajusta si tu modelo de ventas tiene otro nombre/namespace

class Cliente extends Model
{
    use HasFactory;

    // La tabla 'clientes' y la PK 'id' son las convenciones por defecto, no hace falta declararlas.
    // La tabla tiene created_at/updated_at, por eso NO desactivamos timestamps.

    protected $fillable = [
        'nombre',
        'ciudad',
        'tipo_documento',
        'numero_documento',
        'telefono',
        'direccion',
        'frecuente',
    ];

    // Valores por defecto (opcional)
    protected $attributes = [
        'frecuente' => false,
    ];

    // Casts para trabajar más cómodamente desde PHP/Blade
    protected $casts = [
        'frecuente'   => 'boolean',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    /**
     * Relación: Un cliente tiene muchas ventas.
     *
     * IMPORTANTE: Ajusta 'cliente_id' si en tu tabla de ventas la FK se llama diferente
     * (por ejemplo 'id_cliente'). Ejemplo:
     *   ->hasMany(Venta::class, 'id_cliente', 'id');
     */
    public function ventas()
    {
        return $this->hasMany(Venta::class, 'cliente_id', 'id');
    }
}
