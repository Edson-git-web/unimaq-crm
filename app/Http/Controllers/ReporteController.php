<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Cotizacion;
use App\Exports\VentasExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    public function index()
    {
        // KPIs
        $ventasMes = Venta::whereMonth('fecha_venta', now()->month)
                          ->whereYear('fecha_venta', now()->year)
                          ->sum('monto_final');
        
        $ventasAnio = Venta::whereYear('fecha_venta', now()->year)
                           ->sum('monto_final');

        $cotizacionesPendientes = Cotizacion::where('estado', 'Pendiente')->count();
        $cotizacionesAprobadas = Cotizacion::where('estado', 'Aprobada')->count();

        $ultimasVentas = Venta::with('cliente')->latest('id_venta')->take(5)->get();

        return view('reportes.index', compact('ventasMes', 'ventasAnio', 'cotizacionesPendientes', 'cotizacionesAprobadas', 'ultimasVentas'));
    }

    public function exportarExcel()
    {
        return Excel::download(new VentasExport, 'ventas_' . now()->format('Ymd_His') . '.xlsx');
    }

    public function exportarPdf(Request $request)
    {
        $cotizacionId = $request->query('cotizacion_id');
        if (!$cotizacionId) {
            return back()->with('error', 'Se requiere el ID de la cotización para exportar a PDF.');
        }

        $cotizacion = Cotizacion::with(['cliente', 'usuario', 'detalles'])->findOrFail($cotizacionId);
        
        $pdf = Pdf::loadView('pdf.cotizacion', compact('cotizacion'));
        return $pdf->download('cotizacion_' . $cotizacion->codigo . '.pdf');
    }
}
