@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Listado de Ventas</h2>
        <a href="{{ route('ventas.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Registrar Venta Directa</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Código Venta</th>
                            <th>Cotización Ref.</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Monto Final</th>
                            <th>Estado Pago</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ventas as $venta)
                            <tr>
                                <td><strong>{{ $venta->codigo }}</strong></td>
                                <td>
                                    @if($venta->cotizacion)
                                        <a href="{{ route('cotizaciones.show', $venta->cotizacion) }}" class="text-decoration-none">{{ $venta->cotizacion->codigo }}</a>
                                    @else
                                        <span class="text-muted">N/A (Directa)</span>
                                    @endif
                                </td>
                                <td>{{ $venta->cliente->razon_social ?? 'S/N' }}</td>
                                <td>{{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y') }}</td>
                                <td>S/ {{ number_format($venta->monto_final, 2) }}</td>
                                <td>
                                    @php
                                        $badge = 'secondary';
                                        if($venta->estado_pago == 'Pagado total') $badge = 'success';
                                        elseif($venta->estado_pago == 'Pagado parcial') $badge = 'warning text-dark';
                                        elseif($venta->estado_pago == 'Anulado') $badge = 'danger';
                                    @endphp
                                    <span class="badge bg-{{ $badge }}">{{ $venta->estado_pago }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('ventas.show', $venta) }}" class="btn btn-sm btn-outline-info" title="Ver Detalle"><i class="bi bi-eye"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">No hay ventas registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($ventas->hasPages())
        <div class="card-footer bg-white pb-0">
            {{ $ventas->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection
