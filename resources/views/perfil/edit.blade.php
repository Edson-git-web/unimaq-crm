@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0 text-dark">Mi Perfil</h2>
                <p class="text-muted mb-0">Actualiza tu información personal y credenciales</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-4 pb-0">
                <h5 class="fw-bold text-primary mb-0"><i class="bi bi-person-lines-fill me-2"></i>Datos Personales</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('perfil.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3 g-3">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label text-muted small fw-bold text-uppercase">Nombre</label>
                            <input type="text" class="form-control form-control-lg @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $usuario->nombre) }}" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="apellido" class="form-label text-muted small fw-bold text-uppercase">Apellido</label>
                            <input type="text" class="form-control form-control-lg @error('apellido') is-invalid @enderror" id="apellido" name="apellido" value="{{ old('apellido', $usuario->apellido) }}" required>
                            @error('apellido')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label text-muted small fw-bold text-uppercase">Correo Electrónico</label>
                        <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $usuario->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="border-secondary opacity-25 my-4">
                    
                    <h5 class="fw-bold text-secondary mb-3"><i class="bi bi-shield-lock me-2"></i>Seguridad <small class="text-muted fs-6 fw-normal">(Opcional)</small></h5>

                    <div class="mb-3">
                        <label for="current_password" class="form-label text-muted small fw-bold text-uppercase">Contraseña Actual</label>
                        <input type="password" class="form-control form-control-lg @error('current_password') is-invalid @enderror" id="current_password" name="current_password" placeholder="Solo si deseas cambiarla">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-4 g-3">
                        <div class="col-md-6">
                            <label for="new_password" class="form-label text-muted small fw-bold text-uppercase">Nueva Contraseña</label>
                            <input type="password" class="form-control form-control-lg @error('new_password') is-invalid @enderror" id="new_password" name="new_password">
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="new_password_confirmation" class="form-label text-muted small fw-bold text-uppercase">Confirmar Nueva Contraseña</label>
                            <input type="password" class="form-control form-control-lg" id="new_password_confirmation" name="new_password_confirmation">
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4 pt-3 border-top">
                        <a href="{{ route('dashboard') }}" class="btn btn-light fw-bold px-4">Cancelar</a>
                        <button type="submit" class="btn btn-primary fw-bold px-4"><i class="bi bi-save me-2"></i>Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
