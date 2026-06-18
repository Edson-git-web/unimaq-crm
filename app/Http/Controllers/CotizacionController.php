<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\DetalleCotizacion;
use App\Models\Cliente;
use App\Http\Requests\CotizacionRequest;
use App\Services\AuditoriaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CotizacionController extends Controller
{
    public function index()
    {
        $cotizaciones = Cotizacion::with(['cliente', 'usuario'])->orderBy('id_cotizacion', 'desc')->paginate(10);
        return view('cotizaciones.index', compact('cotizaciones'));
    }

    public function create()
    {
        $clientes = Cliente::orderBy('razon_social', 'asc')->get();
        return view('cotizaciones.create', compact('clientes'));
    }

    public function store(CotizacionRequest $request)
    {
        try {
            DB::beginTransaction();

            // ponytail: lockForUpdate para evitar race conditions (H-002)
            $maxId = Cotizacion::lockForUpdate()->max('id_cotizacion') ?? 0;
            $codigo = 'COT-' . str_pad($maxId + 1, 5, '0', STR_PAD_LEFT);

            $cotizacion = new Cotizacion();
            $cotizacion->codigo = $codigo;
            $cotizacion->id_cliente = $request->id_cliente;
            $cotizacion->id_usuario = Auth::id();
            $cotizacion->fecha_emision = now()->toDateString();
            $cotizacion->fecha_vence = $request->fecha_vence;
            $cotizacion->observaciones = $request->observaciones;
            $cotizacion->estado = 'Pendiente';
            
            $cotizacion->save();

            $subtotal = 0;

            foreach ($request->detalles as $detalleData) {
                $detalle = new DetalleCotizacion();
                $detalle->id_cotizacion = $cotizacion->id_cotizacion;
                $detalle->descripcion = $detalleData['descripcion'];
                $detalle->cantidad = $detalleData['cantidad'];
                $detalle->precio_unit = $detalleData['precio_unit'];
                
                $subtotal += ($detalleData['cantidad'] * $detalleData['precio_unit']);
                $detalle->save();
            }

            $igv = collect([$subtotal * 0.18])->round(2)->first();
            $total = collect([$subtotal + $igv])->round(2)->first();

            $cotizacion->monto_subtotal = $subtotal;
            $cotizacion->igv = $igv;
            $cotizacion->monto_total = $total;
            $cotizacion->save();

            AuditoriaService::registrar('CREATE', 'cotizaciones', $cotizacion->id_cotizacion, null, $cotizacion->toArray());

            DB::commit();

            return redirect()->route('cotizaciones.show', $cotizacion)->with('success', 'Cotización creada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ocurrió un error al crear la cotización: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Cotizacion $cotizacion)
    {
        $cotizacion->load(['cliente', 'usuario', 'detalles']);
        return view('cotizaciones.show', compact('cotizacion'));
    }

    public function edit(Cotizacion $cotizacion)
    {
        return redirect()->route('cotizaciones.index')->with('error', 'La edición de cotizaciones no está habilitada en esta versión.');
    }

    public function update(Request $request, Cotizacion $cotizacion)
    {
        return redirect()->route('cotizaciones.index');
    }

    public function destroy(Cotizacion $cotizacion)
    {
        // Cancelar / eliminar no requerido, solo cambio de estado, pero por resource lo definimos
        return redirect()->route('cotizaciones.index');
    }

    public function cambiarEstado(Request $request, Cotizacion $cotizacion)
    {
        $request->validate([
            'estado' => 'required|in:Pendiente,Aprobada,Rechazada,Cerrada,Expirada'
        ]);

        $nuevoEstado = $request->estado;
        $estadoActual = $cotizacion->estado;

        // ponytail: map of valid transitions. Minimum code.
        $transicionesValidas = [
            'Pendiente' => ['Aprobada', 'Rechazada', 'Expirada'],
            'Aprobada' => ['Cerrada']
        ];

        if (!in_array($nuevoEstado, $transicionesValidas[$estadoActual] ?? [])) {
            return redirect()->back()->with('error', "No se puede pasar de $estadoActual a $nuevoEstado.");
        }

        $datosAntes = $cotizacion->toArray();
        $cotizacion->estado = $nuevoEstado;
        $cotizacion->save();

        AuditoriaService::registrar('UPDATE_STATUS', 'cotizaciones', $cotizacion->id_cotizacion, $datosAntes, $cotizacion->toArray());

        return redirect()->back()->with('success', 'Estado actualizado a ' . $nuevoEstado);
    }
}
