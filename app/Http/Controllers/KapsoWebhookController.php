<?php

namespace App\Http\Controllers;

use App\Models\Mensagem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KapsoWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $data = $request->all();
        Log::info('Kapso webhook recebido', $data);

        $providerId = $data['message_id'] ?? $data['id'] ?? null;
        $status     = $data['status'] ?? null;

        if ($providerId && $status) {
            $mensagem = Mensagem::withoutGlobalScopes()
                ->where('provider_message_id', $providerId)
                ->first();

            if ($mensagem) {
                $statusMap = [
                    'delivered' => 'entregue',
                    'read'      => 'lida',
                    'failed'    => 'falhou',
                    'sent'      => 'enviada',
                ];
                $novoStatus = $statusMap[$status] ?? null;
                if ($novoStatus) {
                    $mensagem->update(['status' => $novoStatus]);
                }
            }
        }

        return response()->json(['ok' => true]);
    }
}
