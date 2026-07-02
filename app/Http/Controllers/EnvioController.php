<?php

namespace App\Http\Controllers;

use App\Jobs\EnviarMensagemJob;
use App\Models\Cliente;
use App\Models\Mensagem;
use App\Models\Template;
use Illuminate\Http\Request;

class EnvioController extends Controller
{
    public function enviar(Request $request, Cliente $cliente)
    {
        $data = $request->validate([
            'template_id' => 'required|exists:templates,id',
        ]);

        $template = Template::findOrFail($data['template_id']);

        if ($cliente->opt_out) {
            return back()->with('erro', 'Cliente optou por não receber mensagens (opt-out).');
        }

        $mensagem = Mensagem::create([
            'empresa_id'  => auth()->user()->empresa_id,
            'cliente_id'  => $cliente->id,
            'template_id' => $template->id,
            'status'      => 'pendente',
        ]);

        EnviarMensagemJob::dispatchSync($mensagem);

        if ($mensagem->fresh()->status === 'falhou') {
            return back()->with('erro', 'Falha ao enviar mensagem: ' . $mensagem->fresh()->erro);
        }

        return back()->with('sucesso', 'Mensagem enviada com sucesso.');
    }
}
