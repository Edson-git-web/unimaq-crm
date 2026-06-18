@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Listado de Cotizaciones</h2>
        <a href="{{ route('cotizaciones.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Nueva Cotización</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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
