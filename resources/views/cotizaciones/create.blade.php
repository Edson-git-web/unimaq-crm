@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Generar Cotización</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('cotizaciones.store') }}" method="POST" id="cotizacionForm">
                        @csrf
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="id_cliente" class="form-label">Cliente <span class="text-danger">*</span></label>
                                <select name="id_cliente" id="id_cliente" class="form-select @error('id_cliente') is-invalid @enderror" required>
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
                                <label for="fecha_vence" class="form-label">Fecha de Vencimiento <span class="text-danger">*</span></label>
                                <input type="date" name="fecha_vence" id="fecha_vence" class="form-control @error('fecha_vence') is-invalid @enderror" value="{{ old('fecha_vence', now()->addDays(15)->toDateString()) }}" required min="{{ now()->toDateString() }}">
                                @error('fecha_vence')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="observaciones" class="form-label">Observaciones</label>
                            <textarea name="observaciones" id="observaciones" rows="2" class="form-control @error('observaciones') is-invalid @enderror">{{ old('observaciones') }}</textarea>
                            @error('observaciones')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <hr>
                        <h5 class="mb-3">Detalles de Cotización</h5>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered" id="detallesTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Descripción <span class="text-danger">*</span></th>
                                        <th style="width: 120px;">Cantidad <span class="text-danger">*</span></th>
                                        <th style="width: 150px;">Precio Unit. <span class="text-danger">*</span></th>
                                        <th style="width: 120px;">Subtotal</th>
                                        <th style="width: 60px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input type="text" name="detalles[0][descripcion]" class="form-control" required maxlength="300">
                                        </td>
                                        <td>
                                            <input type="number" name="detalles[0][cantidad]" class="form-control calc-qty" required min="0.01" step="0.01" value="1">
                                        </td>
                                        <td>
                                            <input type="number" name="detalles[0][precio_unit]" class="form-control calc-price" required min="0.01" step="0.01" value="0.00">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control row-subtotal" readonly value="0.00">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-outline-danger btn-sm remove-row"><i class="bi bi-x-lg"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5">
                                            <button type="button" class="btn btn-outline-success btn-sm" id="addRow"><i class="bi bi-plus-lg"></i> Agregar Línea</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">SUBTOTAL S/</td>
                                        <td><input type="text" id="granSubtotal" class="form-control fw-bold" readonly value="0.00"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">IGV (18%) S/</td>
                                        <td><input type="text" id="granIgv" class="form-control fw-bold" readonly value="0.00"></td>
                                        <td></td>
                                    </tr>
                                    <tr class="table-info">
                                        <td colspan="3" class="text-end fw-bold fs-5">TOTAL S/</td>
                                        <td><input type="text" id="granTotal" class="form-control fw-bold fs-5 text-primary" readonly value="0.00"></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                        @error('detalles')
                            <div class="text-danger mb-3">{{ $message }}</div>
                        @enderror

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('cotizaciones.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Generar Cotización</button>
                        </div>
                    </form>
                </div>
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

        document.getElementById('granSubtotal').value = sum.toFixed(2);
        document.getElementById('granIgv').value = igv.toFixed(2);
        document.getElementById('granTotal').value = total.toFixed(2);
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
