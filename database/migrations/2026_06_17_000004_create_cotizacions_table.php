<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->increments('id_cotizacion');
            $table->string('codigo', 20)->unique();
            $table->unsignedInteger('id_cliente');
            $table->unsignedInteger('id_usuario');
            $table->date('fecha_emision');
            $table->date('fecha_vence');
            $table->enum('estado', ['Pendiente', 'Aprobada', 'Rechazada', 'Cerrada', 'Expirada'])->default('Pendiente');
            $table->decimal('monto_subtotal', 12, 2)->default(0.00);
            $table->decimal('igv', 12, 2)->default(0.00);
            $table->decimal('monto_total', 12, 2)->default(0.00);
            $table->text('observaciones')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('id_cliente')->references('id_cliente')->on('clientes');
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cotizaciones');
    }
};
