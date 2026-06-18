@extends('layouts.app')

@section('content')
<div class="container text-center mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1 class="display-1 fw-bold text-danger">403</h1>
            <h2 class="mb-4">Acceso Denegado</h2>
            <p class="lead text-muted mb-4">No tienes permisos para acceder a esta sección.</p>
            <a href="{{ route('dashboard') }}" class="btn btn-primary">Volver al Dashboard</a>
        </div>
    </div>
</div>
@endsection
