@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Registrar Nuevo Cliente</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('clientes.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="tipo_cliente" class="form-label">Tipo de Cliente <span class="text-danger">*</span></label>
                            <select name="tipo_cliente" id="tipo_cliente" class="form-select @error('tipo_cliente') is-invalid @enderror" required>
                                <option value="Empresa" {{ old('tipo_cliente') == 'Empresa' ? 'selected' : '' }}>Empresa</option>
                                <option value="Persona Natural" {{ old('tipo_cliente') == 'Persona Natural' ? 'selected' : '' }}>Persona Natural</option>
                            </select>
                            @error('tipo_cliente')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="ruc_dni" class="form-label">RUC / DNI <span class="text-danger">*</span></label>
                            <input type="text" name="ruc_dni" id="ruc_dni" class="form-control @error('ruc_dni') is-invalid @enderror" value="{{ old('ruc_dni') }}" maxlength="11" required>
                            @error('ruc_dni')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="razon_social" class="form-label">Razón Social / Nombres <span class="text-danger">*</span></label>
                            <input type="text" name="razon_social" id="razon_social" class="form-control @error('razon_social') is-invalid @enderror" value="{{ old('razon_social') }}" required>
                            @error('razon_social')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" name="telefono" id="telefono" class="form-control @error('telefono') is-invalid @enderror" value="{{ old('telefono') }}">
                            @error('telefono')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" name="direccion" id="direccion" class="form-control @error('direccion') is-invalid @enderror" value="{{ old('direccion') }}">
                            @error('direccion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Guardar Cliente</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
