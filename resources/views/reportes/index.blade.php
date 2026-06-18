@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0 text-dark">Métricas y Reportes</h2>
        <p class="text-muted mb-0">Análisis del rendimiento comercial</p>
    </div>
    <a href="{{ route('reportes.excel') }}" class="btn btn-success fw-bold shadow-sm"><i class="bi bi-file-earmark-excel me-2"></i>Exportar a Excel</a>
</div>

<div class="row g-4 mb-5">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 bg-primary text-white shadow-sm" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-hover)) !important;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title mb-0 text-white-50 text-uppercase fw-bold">Ventas del Mes</h6>
                    <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-graph-up-arrow fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0">S/ {{ number_format($ventasMes, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 bg-success text-white shadow-sm" style="background: linear-gradient(135deg, #198754, #146c43) !important;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title mb-0 text-white-50 text-uppercase fw-bold">Ventas del Año</h6>
                    <div class="bg-white text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-cash-stack fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0">S/ {{ number_format($ventasAnio, 2) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title mb-0 text-muted text-uppercase fw-bold">Cotiz. Pendientes</h6>
                    <div class="bg-light text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-hourglass-split fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 text-dark">{{ $cotizacionesPendientes }}</h3>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title mb-0 text-muted text-uppercase fw-bold">Cotiz. Aprobadas</h6>
                    <div class="bg-light text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-check-all fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 text-dark">{{ $cotizacionesAprobadas }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 pt-4 pb-3">
        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Últimas Ventas Registradas</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Código</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Monto Total</th>
                        <th class="pe-4">Estado Pago</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse($ultimasVentas as $venta)
                        <tr>
                            <td class="ps-4"><span class="fw-bold text-primary">{{ $venta->codigo }}</span></td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $venta->cliente->razon_social ?? 'S/N' }}</div>
                            </td>
                            <td class="text-muted"><i class="bi bi-calendar-check me-1"></i>{{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y') }}</td>
                            <td><span class="fw-bold">S/ {{ number_format($venta->monto_final, 2) }}</span></td>
                            <td class="pe-4">
                                @php
                                    $badgeClass = match($venta->estado_pago) {
                                        'Pagado total' => 'bg-success',
                                        'Pagado parcial' => 'bg-warning text-dark',
                                        'Pendiente' => 'bg-secondary',
                                        'Anulado' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill">{{ $venta->estado_pago }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-receipt fs-1 d-block mb-3"></i>
                                No hay ventas recientes.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
