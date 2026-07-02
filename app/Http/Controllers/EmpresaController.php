<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function edit()
    {
        $empresa = auth()->user()->empresa;
        return view('empresas.edit', compact('empresa'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'whatsapp_numero' => 'nullable|string|max:20',
            'kapso_sender_id' => 'nullable|string|max:255',
        ]);

        auth()->user()->empresa->update($data);

        return redirect()->route('empresa.edit')->with('sucesso', 'Configurações atualizadas com sucesso.');
    }
}
