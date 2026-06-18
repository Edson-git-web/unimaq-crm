@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0 text-dark">Panel de Control</h2>
        <p class="text-muted mb-0">Bienvenido de nuevo, {{ Auth::user()->nombre }}. Rol: {{ Auth::user()->rol->nombre_rol ?? 'Sin rol' }}</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 bg-primary text-white shadow-sm" style="background: linear-gradient(135deg, var(--primary-color), var(--primary-hover)) !important;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title mb-0 text-white-50 text-uppercase fw-bold">Ventas del Mes</h6>
                    <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-currency-dollar fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0">S/ {{ number_format($ventasMes, 2) }}</h3>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title mb-0 text-muted text-uppercase fw-bold">Clientes Totales</h6>
                    <div class="bg-light text-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-people fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 text-dark">{{ $clientesCount }}</h3>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title mb-0 text-muted text-uppercase fw-bold">Cotizaciones</h6>
                    <div class="bg-light text-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-file-text fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 text-dark">{{ $cotizacionesCount }}</h3>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-title mb-0 text-muted text-uppercase fw-bold">Cotizaciones Pend.</h6>
                    <div class="bg-light text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="bi bi-clock-history fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-0 text-dark">{{ $cotizacionesPendientes }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-8 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 fw-bold text-dark">Accesos Rápidos</h6>
            </div>
            <div class="card-body">
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('cotizaciones.create') }}" class="btn btn-outline-primary shadow-sm"><i class="bi bi-plus-circle me-2"></i>Nueva Cotización</a>
                    <a href="{{ route('clientes.create') }}" class="btn btn-outline-secondary shadow-sm"><i class="bi bi-person-plus me-2"></i>Nuevo Cliente</a>
                    <a href="{{ route('ventas.index') }}" class="btn btn-outline-secondary shadow-sm"><i class="bi bi-cart me-2"></i>Ver Ventas</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4 mb-4">
        <div class="card border-0 shadow-sm bg-dark text-white h-100" style="background: var(--sidebar-bg) !important;">
            <div class="card-body text-center d-flex flex-column justify-content-center py-5">
                <img src="{{ asset('images/crm.png') }}" alt="UNIMAQ" class="img-fluid mx-auto mb-3" style="max-height: 60px;">
                <h5 class="fw-bold">Soporte Técnico</h5>
                <p class="text-white-50 mb-0">Para cualquier inconveniente con el CRM, comuníquese con el administrador del sistema.</p>
            </div>
        </div>
    </div>
</div>
@endsection
