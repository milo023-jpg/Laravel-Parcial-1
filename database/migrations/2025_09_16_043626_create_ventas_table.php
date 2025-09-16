<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();

            // Cliente opcional (puede ser "mostrador")
            $table->foreignId('cliente_id')->nullable()
                  ->constrained('clientes')->nullOnDelete();

            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('valor_total', 10, 2);
            $table->timestamp('fecha_pedido')->useCurrent();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
