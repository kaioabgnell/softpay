<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Template;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes  = Cliente::latest()->paginate(25);
        $templates = Template::all();
        return view('clientes.index', compact('clientes', 'templates'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'     => 'required|string|max:120',
            'telefone' => 'required|string|max:20',
        ]);

        $data['empresa_id'] = auth()->user()->empresa_id;
        $data['opt_out']    = false;

        Cliente::create($data);

        return redirect()->route('clientes.index')->with('sucesso', 'Cliente cadastrado com sucesso.');
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $data = $request->validate([
            'nome'     => 'required|string|max:120',
            'telefone' => 'required|string|max:20',
            'opt_out'  => 'boolean',
        ]);

        $data['opt_out'] = $request->boolean('opt_out');

        $cliente->update($data);

        return redirect()->route('clientes.index')->with('sucesso', 'Cliente atualizado com sucesso.');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return redirect()->route('clientes.index')->with('sucesso', 'Cliente removido.');
    }
}
