<?php

namespace App\Services;

use App\Models\Auditoria;
use Illuminate\Support\Facades\Auth;

class AuditoriaService
{
    public static function registrar($accion, $tabla_afectada = null, $registro_id = null, $datos_antes = null, $datos_despues = null)
    {
        if (is_array($datos_antes) && array_key_exists('password', $datos_antes)) {
            unset($datos_antes['password']);
        }
        if (is_array($datos_despues) && array_key_exists('password', $datos_despues)) {
            unset($datos_despues['password']);
        }

        Auditoria::create([
            'id_usuario' => Auth::id(),
            'accion' => $accion,
            'tabla_afectada' => $tabla_afectada,
            'registro_id' => $registro_id,
            'datos_antes' => $datos_antes,
            'datos_despues' => $datos_despues,
            'ip_origen' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
