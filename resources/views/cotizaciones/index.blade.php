@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0 text-dark">Gestión de Cotizaciones</h2>
        <p class="text-muted mb-0">Listado y filtrado de todas las propuestas comerciales</p>
    </div>
    <a href="{{ route('cotizaciones.create') }}" class="btn btn-primary fw-bold shadow-sm"><i class="bi bi-plus-circle me-2"></i>Nueva Cotización</a>
</div>

<div class="card border-0 shadow-sm mb-4 bg-white">
    <div class="card-body p-4">
        <form action="{{ route('cotizaciones.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label text-muted small fw-bold text-uppercase mb-1">Buscar</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="buscar" class="form-control border-start-0 ps-0" placeholder="Código o cliente..." value="{{ request('buscar') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted small fw-bold text-uppercase mb-1">Estado</label>
                <select name="estado" class="form-select">
                    <option value="">Todos los Estados</option>
                    <option value="Pendiente" {{ request('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="Aprobada" {{ request('estado') == 'Aprobada' ? 'selected' : '' }}>Aprobada</option>
                    <option value="Rechazada" {{ request('estado') == 'Rechazada' ? 'selected' : '' }}>Rechazada</option>
                    <option value="Cerrada" {{ request('estado') == 'Cerrada' ? 'selected' : '' }}>Cerrada</option>
                    <option value="Expirada" {{ request('estado') == 'Expirada' ? 'selected' : '' }}>Expirada</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label text-muted small fw-bold text-uppercase mb-1">Desde</label>
                <input type="date" name="fecha_inicio" class="form-control" title="Fecha inicio (Emisión)" value="{{ request('fecha_inicio') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label text-muted small fw-bold text-uppercase mb-1">Hasta</label>
                <input type="date" name="fecha_fin" class="form-control" title="Fecha fin (Emisión)" value="{{ request('fecha_fin') }}">
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
                        <th class="ps-4">Código</th>
                        <th>Cliente</th>
                        <th>Emisión</th>
                        <th>Vencimiento</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse ($cotizaciones as $cot)
                        <tr>
                            <td class="ps-4"><span class="fw-bold text-primary">{{ $cot->codigo }}</span></td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $cot->cliente->razon_social ?? 'S/N' }}</div>
                            </td>
                            <td class="text-muted"><i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($cot->fecha_emision)->format('d/m/Y') }}</td>
                            <td class="text-muted"><i class="bi bi-calendar-x me-1"></i>{{ \Carbon\Carbon::parse($cot->fecha_vence)->format('d/m/Y') }}</td>
                            <td><span class="fw-bold">S/ {{ number_format($cot->monto_total, 2) }}</span></td>
                            <td>
                                @php
                                    $badgeClass = match($cot->estado) {
                                        'Aprobada' => 'bg-success',
                                        'Rechazada' => 'bg-danger',
                                        'Pendiente' => 'bg-warning text-dark',
                                        'Cerrada' => 'bg-info text-dark',
                                        'Expirada' => 'bg-secondary',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill">{{ $cot->estado }}</span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('cotizaciones.show', $cot) }}" class="btn btn-sm btn-light text-primary border rounded-circle shadow-sm" title="Ver Detalle" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-folder2-open fs-1 d-block mb-3"></i>
                                No se encontraron cotizaciones con los filtros aplicados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($cotizaciones->hasPages())
    <div class="card-footer bg-white border-0 pt-4 pb-3">
        {{ $cotizaciones->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
