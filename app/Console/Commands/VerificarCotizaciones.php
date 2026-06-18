<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cotizacion;

class VerificarCotizaciones extends Command
{
    protected $signature = 'cotizaciones:verificar';
    protected $description = 'Marca como expiradas las cotizaciones pendientes con fecha de vencimiento superada';

    public function handle()
    {
        $expiradas = Cotizacion::where('estado', 'Pendiente')
            ->whereDate('fecha_vence', '<', now()->toDateString())
            ->update(['estado' => 'Expirada']);

        $this->info("Cotizaciones expiradas actualizadas: $expiradas");
    }
}
