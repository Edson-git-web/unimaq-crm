@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Dashboard y Reportes</h2>
        <div>
            <a href="{{ route('reportes.excel') }}" class="btn btn-success"><i class="bi bi-file-earmark-excel"></i> Exportar Ventas a Excel</a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Ventas del Mes</h5>
                    <h3 class="card-text">S/ {{ number_format($ventasMes, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Ventas del Año</h5>
                    <h3 class="card-text">S/ {{ number_format($ventasAnio, 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-dark bg-warning mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Cotiz. Pendientes</h5>
                    <h3 class="card-text">{{ $cotizacionesPendientes }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Cotiz. Aprobadas</h5>
                    <h3 class="card-text">{{ $cotizacionesAprobadas }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white fw-bold">Últimas 5 Ventas Registradas</div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Monto Total</th>
                        <th>Estado Pago</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ultimasVentas as $venta)
                        <tr>
                            <td>{{ $venta->codigo }}</td>
                            <td>{{ $venta->cliente->razon_social ?? 'S/N' }}</td>
                            <td>{{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y') }}</td>
                            <td>S/ {{ number_format($venta->monto_final, 2) }}</td>
                            <td>{{ $venta->estado_pago }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">No hay ventas recientes.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
