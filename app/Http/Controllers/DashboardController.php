<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $clientesCount = \App\Models\Cliente::count();
        $cotizacionesCount = \App\Models\Cotizacion::count();
        $ventasMes = \App\Models\Venta::whereMonth('fecha_venta', now()->month)
                                      ->whereYear('fecha_venta', now()->year)
                                      ->sum('monto_final');
        $cotizacionesPendientes = \App\Models\Cotizacion::where('estado', 'Pendiente')->count();

        return view('dashboard', compact('clientesCount', 'cotizacionesCount', 'ventasMes', 'cotizacionesPendientes'));
    }
}
