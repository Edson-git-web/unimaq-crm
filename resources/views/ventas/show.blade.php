@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Detalle de Venta: {{ $venta->codigo }}</h2>
        <a href="{{ route('ventas.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver a Ventas</a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">Información de Venta</div>
                <div class="card-body">
                    <p><strong>Fecha de Venta:</strong> {{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y') }}</p>
                    <p><strong>Monto Final:</strong> S/ {{ number_format($venta->monto_final, 2) }}</p>
                    <p><strong>Estado de Pago:</strong> 
                        @php
                            $badge = 'secondary';
                            if($venta->estado_pago == 'Pagado total') $badge = 'success';
                            elseif($venta->estado_pago == 'Pagado parcial') $badge = 'warning text-dark';
                            elseif($venta->estado_pago == 'Anulado') $badge = 'danger';
                        @endphp
                        <span class="badge bg-{{ $badge }}">{{ $venta->estado_pago }}</span>
                    </p>
                    <p><strong>Registrada por:</strong> {{ $venta->usuario->nombre ?? 'S/N' }} {{ $venta->usuario->apellido ?? '' }}</p>
                    <p><strong>Observaciones:</strong> {{ $venta->observaciones ?? 'Ninguna' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">Detalles Adicionales</div>
                <div class="card-body">
                    <p><strong>Cliente:</strong> {{ $venta->cliente->razon_social ?? 'S/N' }} (RUC/DNI: {{ $venta->cliente->ruc_dni ?? '' }})</p>
                    @if($venta->cotizacion)
                        <p><strong>Cotización Origen:</strong> <a href="{{ route('cotizaciones.show', $venta->cotizacion) }}">{{ $venta->cotizacion->codigo }}</a></p>
                        <p class="text-muted small">Esta venta fue generada a partir de una cotización. Los detalles de los productos/servicios se encuentran en la cotización.</p>
                    @else
                        <p class="text-muted small">Venta directa (Sin cotización previa vinculada).</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
