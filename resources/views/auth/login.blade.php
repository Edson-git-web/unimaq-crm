@extends('layouts.app')

@section('content')
<div class="auth-wrapper">
    <div class="auth-sidebar col-md-5 col-lg-6 d-none d-md-flex flex-column">
        <img src="{{ asset('images/crm.png') }}" alt="UNIMAQ Logo" style="max-width: 300px; margin-bottom: 2rem;">
        <h2 class="fw-bold">UNIMAQ CRM</h2>
        <p class="text-white-50">Sistema de Gestión de Clientes y Cotizaciones</p>
    </div>
    
    <div class="auth-form-container col-12 col-md-7 col-lg-6">
        <div class="auth-card">
            <div class="text-center d-md-none mb-4">
                <img src="{{ asset('images/crm.png') }}" alt="UNIMAQ Logo" style="max-width: 200px;">
            </div>
            
            <h3 class="fw-bold mb-1 text-dark">Bienvenido de nuevo</h3>
            <p class="text-muted mb-4">Ingresa tus credenciales para continuar.</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label text-dark fw-semibold">{{ __('Correo Electrónico') }}</label>
                    <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="nombre@unimaq.com.pe">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label text-dark fw-semibold d-flex justify-content-between">
                        {{ __('Contraseña') }}
                        @if (Route::has('password.request'))
                            <a class="text-decoration-none text-primary fs-6" href="{{ route('password.request') }}">
                                {{ __('¿Olvidaste tu contraseña?') }}
                            </a>
                        @endif
                    </label>
                    <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="••••••••">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="mb-4 form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label text-muted" for="remember">
                        {{ __('Mantener sesión iniciada') }}
                    </label>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg fw-bold">
                        {{ __('Ingresar al Sistema') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
