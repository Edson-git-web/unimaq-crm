@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Registro de Auditoría</h2>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body bg-light">
            <form action="{{ route('auditoria.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small">Usuario</label>
                    <select name="id_usuario" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        @foreach($usuarios as $user)
                            <option value="{{ $user->id_usuario }}" {{ request('id_usuario') == $user->id_usuario ? 'selected' : '' }}>
                                {{ $user->nombre }} {{ $user->apellido }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Tabla Afectada</label>
                    <input type="text" name="tabla_afectada" class="form-control form-control-sm" value="{{ request('tabla_afectada') }}" placeholder="ej. clientes, ventas">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Fecha Inicio</label>
                    <input type="date" name="fecha_inicio" class="form-control form-control-sm" value="{{ request('fecha_inicio') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small">Fecha Fin</label>
                    <input type="date" name="fecha_fin" class="form-control form-control-sm" value="{{ request('fecha_fin') }}">
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i> Filtrar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0" style="font-size: 0.9rem;">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha/Hora</th>
                            <th>Usuario</th>
                            <th>Acción</th>
                            <th>Tabla</th>
                            <th>ID Reg.</th>
                            <th>Datos Antes</th>
                            <th>Datos Después</th>
                            <th>IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($auditorias as $audit)
                            <tr>
                                <td>{{ $audit->fecha_hora }}</td>
                                <td>{{ $audit->nombre }} {{ $audit->apellido }}</td>
                                <td><span class="badge bg-secondary">{{ $audit->accion }}</span></td>
                                <td>{{ $audit->tabla_afectada }}</td>
                                <td>{{ $audit->registro_id }}</td>
                                <td>
                                    @if($audit->datos_antes)
                                        <button type="button" class="btn btn-xs btn-outline-secondary" onclick="alert(JSON.stringify({{ $audit->datos_antes }}, null, 2))">Ver</button>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($audit->datos_despues)
                                        <button type="button" class="btn btn-xs btn-outline-secondary" onclick="alert(JSON.stringify({{ $audit->datos_despues }}, null, 2))">Ver</button>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $audit->ip_origen }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No hay registros de auditoría que coincidan con los filtros.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($auditorias->hasPages())
        <div class="card-footer bg-white pb-0">
            {{ $auditorias->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
@endsection
