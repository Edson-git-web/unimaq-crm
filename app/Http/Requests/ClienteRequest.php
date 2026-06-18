<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $clienteId = $this->route('cliente') ? $this->route('cliente')->id_cliente : null;

        return [
            'ruc_dni' => 'required|string|max:11|unique:clientes,ruc_dni,' . $clienteId . ',id_cliente',
            'razon_social' => 'required|string|max:255',
            'tipo_cliente' => 'required|in:Persona Natural,Empresa',
            'email' => 'nullable|email|max:150|unique:clientes,email,' . $clienteId . ',id_cliente',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:300',
        ];
    }
}
