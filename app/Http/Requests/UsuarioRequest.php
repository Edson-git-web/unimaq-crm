<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $usuarioId = $this->route('usuario') ? $this->route('usuario')->id_usuario : null;

        $rules = [
            'nombre'    => 'required|string|max:100',
            'apellido'  => 'required|string|max:100',
            'email'     => 'required|email|unique:usuarios,email,' . $usuarioId . ',id_usuario',
            'id_rol'    => 'required|exists:roles,id_rol',
        ];

        if ($this->isMethod('post') || $this->filled('password')) {
            $rules['password'] = 'required|min:8|confirmed';
        }

        return $rules;
    }
}
