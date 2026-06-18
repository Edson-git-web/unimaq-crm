@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        @if($cotizacion)
                            Generar Venta desde Cotización: {{ $cotizacion->codigo }}
                        @else
                            Registrar Venta Directa
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('ventas.store') }}" method="POST">
                        @csrf
                        
                        @if($cotizacion)
                            <input type="hidden" name="id_cotizacion" value="{{ $cotizacion->id_cotizacion }}">
                        @endif

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="id_cliente" class="form-label">Cliente <span class="text-danger">*</span></label>
                                @if($cotizacion)
                                    <input type="hidden" name="id_cliente" value="{{ $cotizacion->id_cliente }}">
                                    <input type="text" class="form-control" value="{{ $cotizacion->cliente->razon_social }}" readonly>
                                @else
                                    <select name="id_cliente" id="id_cliente" class="form-select @error('id_cliente') is-invalid @enderror" required>
                                        <option value="">-- Seleccione Cliente --</option>
                                        @foreach($clientes as $cliente)
                                            <option value="{{ $cliente->id_cliente }}" {{ old('id_cliente') == $cliente->id_cliente ? 'selected' : '' }}>
                                                {{ $cliente->ruc_dni }} - {{ $cliente->razon_social }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_cliente')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="fecha_venta" class="form-label">Fecha de Venta <span class="text-danger">*</span></label>
                                <input type="date" name="fecha_venta" id="fecha_venta" class="form-control @error('fecha_venta') is-invalid @enderror" value="{{ old('fecha_venta', now()->toDateString()) }}" required>
                                @error('fecha_venta')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="monto_final" class="form-label">Monto Final (S/) <span class="text-danger">*</span></label>
                                <input type="number" name="monto_final" id="monto_final" class="form-control @error('monto_final') is-invalid @enderror" value="{{ old('monto_final', $cotizacion ? $cotizacion->monto_total : '0.00') }}" required min="0.01" step="0.01" {{ $cotizacion ? 'readonly' : '' }}>
                                @error('monto_final')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="estado_pago" class="form-label">Estado de Pago <span class="text-danger">*</span></label>
                                <select name="estado_pago" id="estado_pago" class="form-select @error('estado_pago') is-invalid @enderror" required>
                                    <option value="Pendiente" {{ old('estado_pago') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="Pagado parcial" {{ old('estado_pago') == 'Pagado parcial' ? 'selected' : '' }}>Pagado parcial</option>
                                    <option value="Pagado total" {{ old('estado_pago') == 'Pagado total' ? 'selected' : '' }}>Pagado total</option>
                                </select>
                                @error('estado_pago')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="observaciones" class="form-label">Observaciones</label>
                            <textarea name="observaciones" id="observaciones" rows="2" class="form-control @error('observaciones') is-invalid @enderror">{{ old('observaciones') }}</textarea>
                            @error('observaciones')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('ventas.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Registrar Venta</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
