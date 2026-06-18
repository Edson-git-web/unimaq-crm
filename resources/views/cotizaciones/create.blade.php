@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10 col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0 text-dark">Nueva Cotización</h2>
                <p class="text-muted mb-0">Generar una propuesta comercial para un cliente</p>
            </div>
            <a href="{{ route('cotizaciones.index') }}" class="btn btn-light shadow-sm fw-bold border"><i class="bi bi-arrow-left me-2"></i>Volver</a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-4 pb-0">
                <h5 class="fw-bold text-primary mb-0"><i class="bi bi-file-earmark-text me-2"></i>Datos Generales</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('cotizaciones.store') }}" method="POST" id="cotizacionForm">
                    @csrf
                    
                    <div class="row mb-3 g-3">
                        <div class="col-md-6">
                            <label for="id_cliente" class="form-label text-muted small fw-bold text-uppercase">Cliente <span class="text-danger">*</span></label>
                            <select name="id_cliente" id="id_cliente" class="form-select form-select-lg @error('id_cliente') is-invalid @enderror" required>
                                <option value="">-- Seleccione Cliente --</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id_cliente }}" {{ old('id_cliente') == $cliente->id_cliente ? 'selected' : '' }}>
                                        {{ $cliente->ruc_dni }} - {{ $cliente->razon_social }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_cliente')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_vence" class="form-label text-muted small fw-bold text-uppercase">Fecha de Vencimiento <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_vence" id="fecha_vence" class="form-control form-control-lg @error('fecha_vence') is-invalid @enderror" value="{{ old('fecha_vence', now()->addDays(15)->toDateString()) }}" required min="{{ now()->toDateString() }}">
                            @error('fecha_vence')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="observaciones" class="form-label text-muted small fw-bold text-uppercase">Observaciones</label>
                        <textarea name="observaciones" id="observaciones" rows="2" class="form-control @error('observaciones') is-invalid @enderror" placeholder="Condiciones de pago, tiempo de entrega, etc.">{{ old('observaciones') }}</textarea>
                        @error('observaciones')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <hr class="border-secondary opacity-25 my-4">
                    <h5 class="fw-bold text-secondary mb-3"><i class="bi bi-list-check me-2"></i>Detalles de Cotización</h5>
                    
                    <div class="table-responsive border rounded mb-3">
                        <table class="table table-borderless align-middle mb-0" id="detallesTable">
                            <thead class="table-light border-bottom">
                                <tr>
                                    <th class="text-muted small fw-bold text-uppercase ps-3">Descripción <span class="text-danger">*</span></th>
                                    <th class="text-muted small fw-bold text-uppercase" style="width: 120px;">Cantidad <span class="text-danger">*</span></th>
                                    <th class="text-muted small fw-bold text-uppercase" style="width: 150px;">Precio Unit. <span class="text-danger">*</span></th>
                                    <th class="text-muted small fw-bold text-uppercase" style="width: 120px;">Subtotal</th>
                                    <th style="width: 60px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="ps-3 pt-3">
                                        <input type="text" name="detalles[0][descripcion]" class="form-control bg-light" required maxlength="300" placeholder="Ej. Servicio de Mantenimiento">
                                    </td>
                                    <td class="pt-3">
                                        <input type="number" name="detalles[0][cantidad]" class="form-control bg-light calc-qty" required min="0.01" step="0.01" value="1">
                                    </td>
                                    <td class="pt-3">
                                        <input type="number" name="detalles[0][precio_unit]" class="form-control bg-light calc-price" required min="0.01" step="0.01" value="0.00">
                                    </td>
                                    <td class="pt-3">
                                        <input type="text" class="form-control bg-white border-0 fw-bold text-dark row-subtotal" readonly value="0.00">
                                    </td>
                                    <td class="pt-3">
                                        <button type="button" class="btn btn-light text-danger border rounded-circle shadow-sm remove-row" style="width: 35px; height: 35px; padding: 0;"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot class="border-top">
                                <tr>
                                    <td colspan="5" class="p-3">
                                        <button type="button" class="btn btn-outline-primary btn-sm fw-bold" id="addRow"><i class="bi bi-plus-lg me-1"></i> Agregar Línea</button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="row justify-content-end mb-4">
                        <div class="col-md-5 col-lg-4">
                            <div class="bg-light rounded p-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted fw-bold">SUBTOTAL:</span>
                                    <span class="fw-bold text-dark">S/ <span id="granSubtotal">0.00</span></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted fw-bold">IGV (18%):</span>
                                    <span class="fw-bold text-dark">S/ <span id="granIgv">0.00</span></span>
                                </div>
                                <hr class="my-2">
                                <div class="d-flex justify-content-between">
                                    <span class="text-primary fw-bold fs-5">TOTAL:</span>
                                    <span class="text-primary fw-bold fs-5">S/ <span id="granTotal">0.00</span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @error('detalles')
                        <div class="text-danger mb-3">{{ $message }}</div>
                    @enderror

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4 pt-3 border-top">
                        <a href="{{ route('cotizaciones.index') }}" class="btn btn-light fw-bold px-4">Cancelar</a>
                        <button type="submit" class="btn btn-primary fw-bold px-4"><i class="bi bi-save me-2"></i>Generar Cotización</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let rowIndex = 1;
    
    const tableBody = document.querySelector('#detallesTable tbody');
    const addRowBtn = document.getElementById('addRow');

    const updateTotals = () => {
        let sum = 0;
        document.querySelectorAll('#detallesTable tbody tr').forEach(row => {
            const qty = parseFloat(row.querySelector('.calc-qty').value) || 0;
            const price = parseFloat(row.querySelector('.calc-price').value) || 0;
            const sub = qty * price;
            row.querySelector('.row-subtotal').value = sub.toFixed(2);
            sum += sub;
        });

        const igvRate = {{ config('unimaq.igv') }};
        const igv = sum * igvRate;
        const total = sum + igv;

        document.getElementById('granSubtotal').textContent = sum.toFixed(2);
        document.getElementById('granIgv').textContent = igv.toFixed(2);
        document.getElementById('granTotal').textContent = total.toFixed(2);
    };

    addRowBtn.addEventListener('click', function() {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><input type="text" name="detalles[${rowIndex}][descripcion]" class="form-control" required maxlength="300"></td>
            <td><input type="number" name="detalles[${rowIndex}][cantidad]" class="form-control calc-qty" required min="0.01" step="0.01" value="1"></td>
            <td><input type="number" name="detalles[${rowIndex}][precio_unit]" class="form-control calc-price" required min="0.01" step="0.01" value="0.00"></td>
            <td><input type="text" class="form-control row-subtotal" readonly value="0.00"></td>
            <td><button type="button" class="btn btn-outline-danger btn-sm remove-row"><i class="bi bi-x-lg"></i></button></td>
        `;
        tableBody.appendChild(tr);
        rowIndex++;
    });

    tableBody.addEventListener('click', function(e) {
        if(e.target.closest('.remove-row')) {
            if(tableBody.querySelectorAll('tr').length > 1) {
                e.target.closest('tr').remove();
                updateTotals();
            } else {
                alert('Debe haber al menos un detalle en la cotización.');
            }
        }
    });

    tableBody.addEventListener('input', function(e) {
        if(e.target.classList.contains('calc-qty') || e.target.classList.contains('calc-price')) {
            updateTotals();
        }
    });
});
</script>
@endsection
