@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0 text-dark">Gestión de Usuarios</h2>
        <p class="text-muted mb-0">Administración de accesos y personal</p>
    </div>
    <a href="{{ route('usuarios.create') }}" class="btn btn-primary fw-bold shadow-sm"><i class="bi bi-person-plus me-2"></i>Nuevo Usuario</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Usuario</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse ($usuarios as $usuario)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold" style="width: 40px; height: 40px;">
                                        {{ substr($usuario->nombre, 0, 1) }}{{ substr($usuario->apellido, 0, 1) }}
                                    </div>
                                    <div class="fw-bold text-dark">{{ $usuario->nombre }} {{ $usuario->apellido }}</div>
                                </div>
                            </td>
                            <td><a href="mailto:{{ $usuario->email }}" class="text-decoration-none text-muted">{{ $usuario->email }}</a></td>
                            <td><span class="badge bg-secondary px-3 py-2 rounded-pill"><i class="bi bi-shield-check me-1"></i>{{ $usuario->rol->nombre_rol ?? 'S/N' }}</span></td>
                            <td>
                                @if($usuario->estado)
                                    <span class="badge bg-success px-3 py-2 rounded-pill"><i class="bi bi-check-circle me-1"></i>Activo</span>
                                @else
                                    <span class="badge bg-danger px-3 py-2 rounded-pill"><i class="bi bi-x-circle me-1"></i>Inactivo</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-sm btn-light text-primary border" title="Editar"><i class="bi bi-pencil"></i></a>
                                    @if($usuario->estado)
                                    <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" class="d-inline-block" onsubmit="return confirm('¿Seguro que deseas deshabilitar este usuario?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger border" title="Deshabilitar"><i class="bi bi-person-x"></i></button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-1 d-block mb-3"></i>
                                No hay usuarios registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($usuarios->hasPages())
    <div class="card-footer bg-white border-0 pt-4 pb-3">
        {{ $usuarios->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
