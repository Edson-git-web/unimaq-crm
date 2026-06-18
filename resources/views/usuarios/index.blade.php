@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Listado de Usuarios</h2>
        <a href="{{ route('usuarios.create') }}" class="btn btn-primary"><i class="bi bi-person-plus"></i> Nuevo Usuario</a>
    </div>



    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nombres</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($usuarios as $usuario)
                            <tr>
                                <td>{{ $usuario->nombre }} {{ $usuario->apellido }}</td>
                                <td>{{ $usuario->email }}</td>
                                <td><span class="badge bg-info text-dark">{{ $usuario->rol->nombre_rol ?? 'S/N' }}</span></td>
                                <td>
                                    @if($usuario->estado)
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-sm btn-outline-primary" title="Editar"><i class="bi bi-pencil"></i></a>
                                    @if($usuario->estado)
                                    <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" class="d-inline-block" onsubmit="return confirm('¿Seguro que deseas deshabilitar este usuario?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Deshabilitar"><i class="bi bi-person-x"></i></button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No hay usuarios registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($usuarios->hasPages())
        <div class="card-footer bg-white pb-0">
            {{ $usuarios->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection
