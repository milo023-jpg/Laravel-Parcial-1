<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('clientes', function (Blueprint $table) {
        $table->string('ciudad', 100)->nullable()->after('direccion');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('clientes', function (Blueprint $table) {
        $table->dropColumn('ciudad');
    });
}
};
