<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Usuario;

class AuditoriaController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('auditoria')
            ->join('usuarios', 'auditoria.id_usuario', '=', 'usuarios.id_usuario')
            ->select('auditoria.*', 'usuarios.nombre', 'usuarios.apellido')
            ->orderBy('auditoria.fecha_hora', 'desc');

        if ($request->filled('id_usuario')) {
            $query->where('auditoria.id_usuario', $request->id_usuario);
        }

        if ($request->filled('tabla_afectada')) {
            $query->where('auditoria.tabla_afectada', 'like', '%' . $request->tabla_afectada . '%');
        }

        if ($request->filled('fecha_inicio')) {
            $query->whereDate('auditoria.fecha_hora', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->whereDate('auditoria.fecha_hora', '<=', $request->fecha_fin);
        }

        $auditorias = $query->paginate(15)->withQueryString();
        $usuarios = Usuario::orderBy('nombre')->get();

        return view('auditoria.index', compact('auditorias', 'usuarios'));
    }
}
