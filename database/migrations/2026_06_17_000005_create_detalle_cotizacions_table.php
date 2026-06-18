<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_cotizacion', function (Blueprint $table) {
            $table->increments('id_detalle');
            $table->unsignedInteger('id_cotizacion');
            $table->string('descripcion', 300);
            $table->decimal('cantidad', 10, 2);
            $table->decimal('precio_unit', 12, 2);
            $table->decimal('subtotal', 12, 2)->storedAs('cantidad * precio_unit');

            $table->foreign('id_cotizacion')->references('id_cotizacion')->on('cotizaciones')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_cotizacion');
    }
};
