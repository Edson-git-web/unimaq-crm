<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->increments('id_cliente');
            $table->string('ruc_dni', 11)->unique();
            $table->string('razon_social', 255)->index('idx_clientes_razon');
            $table->enum('tipo_cliente', ['Persona Natural', 'Empresa'])->default('Empresa');
            $table->string('email', 150)->nullable()->unique();
            $table->string('telefono', 20)->nullable();
            $table->string('direccion', 300)->nullable();
            $table->boolean('estado')->default(1);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes(); // Adds deleted_at TIMESTAMP NULL
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
