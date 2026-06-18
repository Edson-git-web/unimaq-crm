@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0 text-dark">Nuevo Cliente</h2>
                <p class="text-muted mb-0">Ingresa los datos del nuevo cliente al sistema</p>
            </div>
            <a href="{{ route('clientes.index') }}" class="btn btn-light shadow-sm fw-bold border"><i class="bi bi-arrow-left me-2"></i>Volver</a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-4 pb-0">
                <h5 class="fw-bold text-primary mb-0"><i class="bi bi-person-badge me-2"></i>Información del Cliente</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('clientes.store') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-3 g-3">
                        <div class="col-md-6">
                            <label for="tipo_cliente" class="form-label text-muted small fw-bold text-uppercase">Tipo de Cliente <span class="text-danger">*</span></label>
                            <select name="tipo_cliente" id="tipo_cliente" class="form-select form-select-lg @error('tipo_cliente') is-invalid @enderror" required>
                                <option value="Empresa" {{ old('tipo_cliente') == 'Empresa' ? 'selected' : '' }}>Empresa</option>
                                <option value="Persona Natural" {{ old('tipo_cliente') == 'Persona Natural' ? 'selected' : '' }}>Persona Natural</option>
                            </select>
                            @error('tipo_cliente')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="ruc_dni" class="form-label text-muted small fw-bold text-uppercase">RUC / DNI <span class="text-danger">*</span></label>
                            <input type="text" name="ruc_dni" id="ruc_dni" class="form-control form-control-lg @error('ruc_dni') is-invalid @enderror" value="{{ old('ruc_dni') }}" maxlength="11" required>
                            @error('ruc_dni')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="razon_social" class="form-label text-muted small fw-bold text-uppercase">Razón Social / Nombres <span class="text-danger">*</span></label>
                        <input type="text" name="razon_social" id="razon_social" class="form-control form-control-lg @error('razon_social') is-invalid @enderror" value="{{ old('razon_social') }}" required>
                        @error('razon_social')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <hr class="border-secondary opacity-25 my-4">
                    <h5 class="fw-bold text-secondary mb-3"><i class="bi bi-telephone me-2"></i>Datos de Contacto</h5>

                    <div class="row mb-3 g-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label text-muted small fw-bold text-uppercase">Email</label>
                            <input type="email" name="email" id="email" class="form-control form-control-lg @error('email') is-invalid @enderror" value="{{ old('email') }}">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="telefono" class="form-label text-muted small fw-bold text-uppercase">Teléfono</label>
                            <input type="text" name="telefono" id="telefono" class="form-control form-control-lg @error('telefono') is-invalid @enderror" value="{{ old('telefono') }}">
                            @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="direccion" class="form-label text-muted small fw-bold text-uppercase">Dirección</label>
                        <input type="text" name="direccion" id="direccion" class="form-control form-control-lg @error('direccion') is-invalid @enderror" value="{{ old('direccion') }}">
                        @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4 pt-3 border-top">
                        <a href="{{ route('clientes.index') }}" class="btn btn-light fw-bold px-4">Cancelar</a>
                        <button type="submit" class="btn btn-primary fw-bold px-4"><i class="bi bi-save me-2"></i>Guardar Cliente</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
