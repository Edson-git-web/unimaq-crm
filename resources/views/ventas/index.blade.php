@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0 text-dark">Registro de Ventas</h2>
        <p class="text-muted mb-0">Listado de transacciones cerradas</p>
    </div>
    <a href="{{ route('ventas.create') }}" class="btn btn-primary fw-bold shadow-sm"><i class="bi bi-plus-circle me-2"></i>Registrar Venta Directa</a>
</div>

<div class="card border-0 shadow-sm mb-4 bg-white">
    <div class="card-body p-4">
        <form action="{{ route('ventas.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label text-muted small fw-bold text-uppercase mb-1">Buscar</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="buscar" class="form-control border-start-0 ps-0" placeholder="Código o cliente..." value="{{ request('buscar') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted small fw-bold text-uppercase mb-1">Estado de Pago</label>
                <select name="estado" class="form-select">
                    <option value="">Todos los Estados</option>
                    <option value="Pendiente" {{ request('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="Pagado parcial" {{ request('estado') == 'Pagado parcial' ? 'selected' : '' }}>Pagado parcial</option>
                    <option value="Pagado total" {{ request('estado') == 'Pagado total' ? 'selected' : '' }}>Pagado total</option>
                    <option value="Anulado" {{ request('estado') == 'Anulado' ? 'selected' : '' }}>Anulado</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label text-muted small fw-bold text-uppercase mb-1">Desde</label>
                <input type="date" name="fecha_inicio" class="form-control" title="Fecha inicio (Venta)" value="{{ request('fecha_inicio') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label text-muted small fw-bold text-uppercase mb-1">Hasta</label>
                <input type="date" name="fecha_fin" class="form-control" title="Fecha fin (Venta)" value="{{ request('fecha_fin') }}">
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-secondary fw-bold"><i class="bi bi-funnel me-2"></i>Filtrar</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Código Venta</th>
                        <th>Cotización Ref.</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Monto Final</th>
                        <th>Estado Pago</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse ($ventas as $venta)
                        <tr>
                            <td class="ps-4"><span class="fw-bold text-primary">{{ $venta->codigo }}</span></td>
                            <td>
                                @if($venta->cotizacion)
                                    <a href="{{ route('cotizaciones.show', $venta->cotizacion) }}" class="text-decoration-none fw-semibold text-secondary"><i class="bi bi-link-45deg me-1"></i>{{ $venta->cotizacion->codigo }}</a>
                                @else
                                    <span class="text-muted"><i class="bi bi-dash"></i> N/A (Directa)</span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $venta->cliente->razon_social ?? 'S/N' }}</div>
                            </td>
                            <td class="text-muted"><i class="bi bi-calendar-check me-1"></i>{{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y') }}</td>
                            <td><span class="fw-bold">S/ {{ number_format($venta->monto_final, 2) }}</span></td>
                            <td>
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
                            <td class="text-end pe-4">
                                <a href="{{ route('ventas.show', $venta) }}" class="btn btn-sm btn-light text-primary border rounded-circle shadow-sm" title="Ver Detalle" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-receipt fs-1 d-block mb-3"></i>
                                No se encontraron ventas con los filtros aplicados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($ventas->hasPages())
    <div class="card-footer bg-white border-0 pt-4 pb-3">
        {{ $ventas->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
