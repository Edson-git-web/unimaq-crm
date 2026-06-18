<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Rol;
use App\Http\Requests\UsuarioRequest;
use App\Services\AuditoriaService;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::with('rol')->orderBy('id_usuario', 'desc')->paginate(10);
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $roles = Rol::all();
        return view('usuarios.create', compact('roles'));
    }

    public function store(UsuarioRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $usuario = Usuario::create($data);

        AuditoriaService::registrar('CREATE', 'usuarios', $usuario->id_usuario, null, $usuario->toArray());

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente.');
    }

    public function edit(Usuario $usuario)
    {
        $roles = Rol::all();
        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(UsuarioRequest $request, Usuario $usuario)
    {
        $data = $request->validated();
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $datosAntes = $usuario->toArray();
        $usuario->update($data);

        AuditoriaService::registrar('UPDATE', 'usuarios', $usuario->id_usuario, $datosAntes, $usuario->toArray());

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy(Usuario $usuario)
    {
        $datosAntes = $usuario->toArray();
        $usuario->estado = 0; // Deshabilitar
        $usuario->save();

        AuditoriaService::registrar('DELETE', 'usuarios', $usuario->id_usuario, $datosAntes, null);

        return redirect()->route('usuarios.index')->with('success', 'Usuario deshabilitado exitosamente.');
    }
}
