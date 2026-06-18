@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0 text-dark">Directorio de Clientes</h2>
        <p class="text-muted mb-0">Gestión de la cartera de clientes de UNIMAQ</p>
    </div>
    <a href="{{ route('clientes.create') }}" class="btn btn-primary fw-bold shadow-sm"><i class="bi bi-plus-circle me-2"></i>Nuevo Cliente</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>RUC/DNI</th>
                        <th>Razón Social</th>
                        <th>Tipo</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @forelse ($clientes as $cliente)
                        <tr>
                            <td class="ps-4 text-muted">#{{ str_pad($cliente->id_cliente, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="fw-semibold">{{ $cliente->ruc_dni }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light text-primary rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold" style="width: 40px; height: 40px;">
                                        {{ substr($cliente->razon_social, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $cliente->razon_social }}</div>
                                        <div class="small text-muted">{{ $cliente->direccion ?? 'Sin dirección' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $cliente->tipo_cliente === 'Empresa' ? 'bg-primary' : 'bg-secondary' }} px-3 py-2 rounded-pill">
                                    {{ $cliente->tipo_cliente }}
                                </span>
                            </td>
                            <td><a href="mailto:{{ $cliente->email }}" class="text-decoration-none">{{ $cliente->email }}</a></td>
                            <td>{{ $cliente->telefono }}</td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-sm btn-light text-primary border" title="Ver Detalle"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-sm btn-light text-primary border" title="Editar"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="d-inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este cliente?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger border" title="Eliminar"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-inboxes fs-1 d-block mb-3"></i>
                                No hay clientes registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($clientes->hasPages())
    <div class="card-footer bg-white border-0 pt-4 pb-3">
        {{ $clientes->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
