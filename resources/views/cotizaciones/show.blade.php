@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Detalle de Cotización: {{ $cotizacion->codigo }}</h2>
        <div>
            <a href="{{ route('cotizaciones.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a>
            @if($cotizacion->estado === 'Aprobada')
                <a href="{{ route('ventas.create', ['cotizacion_id' => $cotizacion->id_cotizacion]) }}" class="btn btn-success"><i class="bi bi-cash"></i> Generar Venta</a>
            @endif
            <a href="{{ route('reportes.pdf', ['cotizacion_id' => $cotizacion->id_cotizacion]) }}" class="btn btn-danger"><i class="bi bi-file-earmark-pdf"></i> Generar PDF</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">Productos / Servicios</div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Descripción</th>
                                <th>Cant.</th>
                                <th>Precio U.</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cotizacion->detalles as $idx => $detalle)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td>{{ $detalle->descripcion }}</td>
                                    <td>{{ $detalle->cantidad }}</td>
                                    <td>S/ {{ number_format($detalle->precio_unit, 2) }}</td>
                                    <td>S/ {{ number_format($detalle->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Subtotal:</td>
                                <td class="fw-bold">S/ {{ number_format($cotizacion->monto_subtotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-end fw-bold">IGV (18%):</td>
                                <td class="fw-bold">S/ {{ number_format($cotizacion->igv, 2) }}</td>
                            </tr>
                            <tr class="table-info">
                                <td colspan="4" class="text-end fw-bold fs-5">TOTAL:</td>
                                <td class="fw-bold fs-5 text-primary">S/ {{ number_format($cotizacion->monto_total, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white fw-bold">Información General</div>
                <div class="card-body">
                    <p class="mb-1 text-muted small">Cliente</p>
                    <p class="fw-bold">{{ $cotizacion->cliente->razon_social ?? 'S/N' }} ({{ $cotizacion->cliente->ruc_dni ?? '' }})</p>

                    <p class="mb-1 text-muted small">Generado por</p>
                    <p class="fw-bold">{{ $cotizacion->usuario->nombre ?? 'S/N' }} {{ $cotizacion->usuario->apellido ?? '' }}</p>

                    <p class="mb-1 text-muted small">Fechas</p>
                    <p class="mb-0"><strong>Emisión:</strong> {{ \Carbon\Carbon::parse($cotizacion->fecha_emision)->format('d/m/Y') }}</p>
                    <p class="mb-3"><strong>Vencimiento:</strong> {{ \Carbon\Carbon::parse($cotizacion->fecha_vence)->format('d/m/Y') }}</p>

                    <p class="mb-1 text-muted small">Estado Actual</p>
                    @php
                        $badge = 'secondary';
                        if($cotizacion->estado == 'Aprobada') $badge = 'success';
                        elseif($cotizacion->estado == 'Rechazada') $badge = 'danger';
                        elseif($cotizacion->estado == 'Pendiente') $badge = 'warning';
                        elseif($cotizacion->estado == 'Cerrada') $badge = 'info';
                    @endphp
                    <span class="badge bg-{{ $badge }} fs-6">{{ $cotizacion->estado }}</span>

                    @if($cotizacion->observaciones)
                        <hr>
                        <p class="mb-1 text-muted small">Observaciones</p>
                        <p class="small">{{ $cotizacion->observaciones }}</p>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white fw-bold">Cambiar Estado</div>
                <div class="card-body">
                    <form action="{{ route('cotizaciones.cambiarEstado', $cotizacion) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="input-group">
                            <select name="estado" class="form-select" required>
                                <option value="Pendiente" {{ $cotizacion->estado == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="Aprobada" {{ $cotizacion->estado == 'Aprobada' ? 'selected' : '' }}>Aprobada</option>
                                <option value="Rechazada" {{ $cotizacion->estado == 'Rechazada' ? 'selected' : '' }}>Rechazada</option>
                                <option value="Cerrada" {{ $cotizacion->estado == 'Cerrada' ? 'selected' : '' }}>Cerrada</option>
                                <option value="Expirada" {{ $cotizacion->estado == 'Expirada' ? 'selected' : '' }}>Expirada</option>
                            </select>
                            <button class="btn btn-outline-primary" type="submit">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
