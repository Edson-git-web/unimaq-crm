<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Http\Requests\VentaRequest;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::with(['cliente', 'usuario', 'cotizacion'])->orderBy('id_venta', 'desc')->paginate(10);
        return view('ventas.index', compact('ventas'));
    }

    public function create(Request $request)
    {
        $clientes = Cliente::orderBy('razon_social', 'asc')->get();
        $cotizacion = null;

        if ($request->has('cotizacion_id')) {
            $cotizacion = Cotizacion::where('id_cotizacion', $request->cotizacion_id)->first();
            if ($cotizacion && $cotizacion->estado !== 'Aprobada') {
                return redirect()->route('cotizaciones.index')->with('error', 'Solo se pueden generar ventas de cotizaciones Aprobadas.');
            }
        }

        return view('ventas.create', compact('clientes', 'cotizacion'));
    }

    public function store(VentaRequest $request)
    {
        try {
            DB::beginTransaction();

            // ponytail: lockForUpdate para evitar race conditions (H-002)
            $maxId = Venta::lockForUpdate()->max('id_venta') ?? 0;
            $codigo = 'VEN-' . str_pad($maxId + 1, 5, '0', STR_PAD_LEFT);

            $venta = new Venta();
            $venta->codigo = $codigo;
            $venta->id_cotizacion = $request->id_cotizacion;
            $venta->id_cliente = $request->id_cliente;
            $venta->id_usuario = Auth::id();
            $venta->fecha_venta = $request->fecha_venta;
            $venta->monto_final = $request->monto_final;
            $venta->estado_pago = $request->estado_pago;
            $venta->observaciones = $request->observaciones;
            
            $venta->save();

            if ($venta->id_cotizacion) {
                $cotizacion = Cotizacion::find($venta->id_cotizacion);
                if ($cotizacion) {
                    $datosAntesCot = $cotizacion->toArray();
                    $cotizacion->estado = 'Cerrada';
                    $cotizacion->save();
                    AuditoriaService::registrar('UPDATE_STATUS', 'cotizaciones', $cotizacion->id_cotizacion, $datosAntesCot, $cotizacion->toArray());
                }
            }

            AuditoriaService::registrar('CREATE', 'ventas', $venta->id_venta, null, $venta->toArray());

            DB::commit();

            return redirect()->route('ventas.index')->with('success', 'Venta registrada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ocurrió un error al registrar la venta: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Venta $venta)
    {
        $venta->load(['cliente', 'usuario', 'cotizacion']);
        return view('ventas.show', compact('venta'));
    }
}
