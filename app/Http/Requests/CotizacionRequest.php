<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CotizacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_cliente' => 'required|exists:clientes,id_cliente',
            'fecha_vence' => 'required|date|after_or_equal:today',
            'observaciones' => 'nullable|string',
            'detalles' => 'required|array|min:1',
            'detalles.*.descripcion' => 'required|string|max:300',
            'detalles.*.cantidad' => 'required|numeric|min:0.01',
            'detalles.*.precio_unit' => 'required|numeric|min:0.01',
        ];
    }
}
