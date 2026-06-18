<?php

namespace App\Exports;

use App\Models\Venta;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VentasExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Venta::with(['cliente', 'usuario', 'cotizacion'])->get();
    }

    public function headings(): array
    {
        return [
            'ID Venta',
            'Código',
            'Cotización Ref.',
            'Cliente',
            'Vendedor',
            'Fecha Venta',
            'Monto Final',
            'Estado Pago',
            'Observaciones'
        ];
    }

    public function map($venta): array
    {
        return [
            $venta->id_venta,
            $venta->codigo,
            $venta->cotizacion ? $venta->cotizacion->codigo : 'Directa',
            $venta->cliente->razon_social ?? 'S/N',
            ($venta->usuario->nombre ?? '') . ' ' . ($venta->usuario->apellido ?? ''),
            $venta->fecha_venta,
            $venta->monto_final,
            $venta->estado_pago,
            $venta->observaciones,
        ];
    }
}
