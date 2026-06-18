@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Detalle del Cliente</h2>
        <a href="{{ route('clientes.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver al listado</a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white fw-bold">
            <i class="bi bi-person-badge"></i> Datos del Cliente
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <p class="mb-1 text-muted small">Razón Social / Nombre</p>
                    <p class="fw-bold fs-5">{{ $cliente->razon_social }}</p>
                </div>
                <div class="col-md-4">
                    <p class="mb-1 text-muted small">RUC / DNI</p>
                    <p class="fw-bold">{{ $cliente->ruc_dni }}</p>
                </div>
                <div class="col-md-4">
                    <p class="mb-1 text-muted small">Tipo de Cliente</p>
                    <p class="fw-bold">{{ $cliente->tipo_cliente }}</p>
                </div>
                <div class="col-md-4">
                    <p class="mb-1 text-muted small">Email</p>
                    <p class="fw-bold">{{ $cliente->email ?? 'S/N' }}</p>
                </div>
                <div class="col-md-4">
                    <p class="mb-1 text-muted small">Teléfono</p>
                    <p class="fw-bold">{{ $cliente->telefono ?? 'S/N' }}</p>
                </div>
                <div class="col-md-4">
                    <p class="mb-1 text-muted small">Dirección</p>
                    <p class="fw-bold">{{ $cliente->direccion ?? 'S/N' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Cotizaciones -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-file-earmark-text"></i> Historial de Cotizaciones</span>
                    <span class="badge bg-primary rounded-pill">{{ $cliente->cotizaciones->count() }}</span>
                </div>
                <div class="card-body p-0">
                    @if($cliente->cotizaciones->isEmpty())
                        <div class="p-4 text-center text-muted">
                            No hay cotizaciones registradas para este cliente.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Código</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th>Total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cliente->cotizaciones as $cotizacion)
                                        @php
                                            $badge = 'secondary';
                                            if($cotizacion->estado == 'Aprobada') $badge = 'success';
                                            elseif($cotizacion->estado == 'Rechazada') $badge = 'danger';
                                            elseif($cotizacion->estado == 'Pendiente') $badge = 'warning text-dark';
                                            elseif($cotizacion->estado == 'Cerrada') $badge = 'info';
                                        @endphp
                                        <tr>
                                            <td><strong>{{ $cotizacion->codigo }}</strong></td>
                                            <td>{{ \Carbon\Carbon::parse($cotizacion->fecha_emision)->format('d/m/Y') }}</td>
                                            <td><span class="badge bg-{{ $badge }}">{{ $cotizacion->estado }}</span></td>
                                            <td>S/ {{ number_format($cotizacion->monto_total, 2) }}</td>
                                            <td>
                                                <a href="{{ route('cotizaciones.show', $cotizacion) }}" class="btn btn-sm btn-outline-primary" title="Ver detalle">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Ventas -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-cash-coin"></i> Historial de Ventas</span>
                    <span class="badge bg-success rounded-pill">{{ $cliente->ventas->count() }}</span>
                </div>
                <div class="card-body p-0">
                    @if($cliente->ventas->isEmpty())
                        <div class="p-4 text-center text-muted">
                            No hay ventas registradas para este cliente.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Código</th>
                                        <th>Fecha</th>
                                        <th>Pago</th>
                                        <th>Monto</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cliente->ventas as $venta)
                                        @php
                                            $badgeVenta = 'warning text-dark';
                                            if($venta->estado_pago == 'Pagado total') $badgeVenta = 'success';
                                            elseif($venta->estado_pago == 'Anulado') $badgeVenta = 'danger';
                                        @endphp
                                        <tr>
                                            <td><strong>{{ $venta->codigo }}</strong></td>
                                            <td>{{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y') }}</td>
                                            <td><span class="badge bg-{{ $badgeVenta }}">{{ $venta->estado_pago }}</span></td>
                                            <td>S/ {{ number_format($venta->monto_final, 2) }}</td>
                                            <td>
                                                <a href="{{ route('ventas.show', $venta) }}" class="btn btn-sm btn-outline-success" title="Ver detalle">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
