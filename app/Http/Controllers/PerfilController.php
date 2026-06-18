<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PerfilController extends Controller
{
    public function edit()
    {
        $usuario = Auth::user();
        return view('perfil.edit', compact('usuario'));
    }

    public function update(Request $request)
    {
        $usuario = Auth::user();

        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:usuarios,email,' . $usuario->id_usuario . ',id_usuario',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:6|confirmed',
        ]);

        $usuario->nombre = $request->nombre;
        $usuario->apellido = $request->apellido;
        $usuario->email = $request->email;

        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $usuario->password)) {
                return back()->withErrors(['current_password' => 'La contraseña actual no es correcta.']);
            }
            $usuario->password = bcrypt($request->new_password);
        }

        $usuario->save();

        return redirect()->route('perfil.edit')->with('success', 'Perfil actualizado exitosamente.');
    }
}
