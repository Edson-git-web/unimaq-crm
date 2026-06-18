<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auditoria', function (Blueprint $table) {
            $table->bigIncrements('id_auditoria');
            $table->unsignedInteger('id_usuario')->nullable();
            $table->string('accion', 100);
            $table->string('tabla_afectada', 50)->nullable();
            $table->unsignedInteger('registro_id')->nullable();
            $table->json('datos_antes')->nullable();
            $table->json('datos_despues')->nullable();
            $table->string('ip_origen', 45)->nullable();
            $table->string('user_agent', 300)->nullable();
            $table->timestamp('fecha_hora')->useCurrent();

            $table->index('fecha_hora', 'idx_auditoria_fecha');
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auditoria');
    }
};
