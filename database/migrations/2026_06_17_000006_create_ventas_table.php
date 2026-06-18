<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->increments('id_venta');
            $table->string('codigo', 20)->unique();
            $table->unsignedInteger('id_cotizacion')->nullable();
            $table->unsignedInteger('id_cliente');
            $table->unsignedInteger('id_usuario');
            $table->date('fecha_venta');
            $table->decimal('monto_final', 12, 2);
            $table->enum('estado_pago', ['Pendiente', 'Pagado parcial', 'Pagado total', 'Anulado'])->default('Pendiente');
            $table->text('observaciones')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('id_cotizacion')->references('id_cotizacion')->on('cotizaciones')->onDelete('set null');
            $table->foreign('id_cliente')->references('id_cliente')->on('clientes');
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
