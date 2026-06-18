<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VentaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_cliente' => 'required|exists:clientes,id_cliente',
            'id_cotizacion' => 'nullable|exists:cotizaciones,id_cotizacion',
            'fecha_venta' => 'required|date',
            'monto_final' => 'required|numeric|min:0.01',
            'estado_pago' => 'required|in:Pendiente,Pagado parcial,Pagado total,Anulado',
            'observaciones' => 'nullable|string',
        ];
    }
}
