@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Listado de Cotizaciones</h2>
        <a href="{{ route('cotizaciones.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Nueva Cotización</a>
    </div>


    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('cotizaciones.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="buscar" class="form-control" placeholder="Buscar por código o cliente..." value="{{ request('buscar') }}">
                </div>
                <div class="col-md-3">
                    <select name="estado" class="form-select">
                        <option value="">-- Todos los Estados --</option>
                        <option value="Pendiente" {{ request('estado') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="Aprobada" {{ request('estado') == 'Aprobada' ? 'selected' : '' }}>Aprobada</option>
                        <option value="Rechazada" {{ request('estado') == 'Rechazada' ? 'selected' : '' }}>Rechazada</option>
                        <option value="Cerrada" {{ request('estado') == 'Cerrada' ? 'selected' : '' }}>Cerrada</option>
                        <option value="Expirada" {{ request('estado') == 'Expirada' ? 'selected' : '' }}>Expirada</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="fecha_inicio" class="form-control" title="Fecha inicio (Emisión)" value="{{ request('fecha_inicio') }}">
                </div>
                <div class="col-md-2">
                    <input type="date" name="fecha_fin" class="form-control" title="Fecha fin (Emisión)" value="{{ request('fecha_fin') }}">
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Filtrar</button>
                </div>
            </form>
        </div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Código</th>
                            <th>Cliente</th>
                            <th>Emisión</th>
                            <th>Vencimiento</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cotizaciones as $cot)
                            <tr>
                                <td><strong>{{ $cot->codigo }}</strong></td>
                                <td>{{ $cot->cliente->razon_social ?? 'S/N' }}</td>
                                <td>{{ \Carbon\Carbon::parse($cot->fecha_emision)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($cot->fecha_vence)->format('d/m/Y') }}</td>
                                <td>S/ {{ number_format($cot->monto_total, 2) }}</td>
                                <td>
                                    @php
                                        $badge = 'secondary';
                                        if($cot->estado == 'Aprobada') $badge = 'success';
                                        elseif($cot->estado == 'Rechazada') $badge = 'danger';
                                        elseif($cot->estado == 'Pendiente') $badge = 'warning';
                                        elseif($cot->estado == 'Cerrada') $badge = 'info';
                                    @endphp
                                    <span class="badge bg-{{ $badge }}">{{ $cot->estado }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('cotizaciones.show', $cot) }}" class="btn btn-sm btn-outline-info" title="Ver"><i class="bi bi-eye"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No hay cotizaciones registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($cotizaciones->hasPages())
        <div class="card-footer bg-white pb-0">
            {{ $cotizaciones->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection
