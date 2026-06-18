<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Http\Requests\ClienteRequest;
use App\Services\AuditoriaService;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::orderBy('id_cliente', 'desc')->paginate(10);
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(ClienteRequest $request)
    {
        $cliente = Cliente::create($request->validated());

        AuditoriaService::registrar('CREATE', 'clientes', $cliente->id_cliente, null, $cliente->toArray());

        return redirect()->route('clientes.index')->with('success', 'Cliente creado exitosamente.');
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(ClienteRequest $request, Cliente $cliente)
    {
        $datosAntes = $cliente->toArray();
        $cliente->update($request->validated());
        
        AuditoriaService::registrar('UPDATE', 'clientes', $cliente->id_cliente, $datosAntes, $cliente->toArray());

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado exitosamente.');
    }

    public function destroy(Cliente $cliente)
    {
        $datosAntes = $cliente->toArray();
        $cliente->delete();

        AuditoriaService::registrar('DELETE', 'clientes', $cliente->id_cliente, $datosAntes, null);

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado exitosamente.');
    }
}
