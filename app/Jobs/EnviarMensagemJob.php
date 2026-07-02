<?php

namespace App\Jobs;

use App\Models\Mensagem;
use App\Services\KapsoService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EnviarMensagemJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public Mensagem $mensagem) {}

    public function handle(KapsoService $kapso): void
    {

        $m = $this->mensagem;

        try {
            $empresa = $m->empresa;
            $template = $m->template;

            if (empty($empresa->kapso_sender_id)) {
                throw new \RuntimeException('Empresa não possui um número do WhatsApp (phone_number_id) configurado.');
            }

            $components = [];
            if ($template->usa_client_name) {
                $components[] = [
                    'type' => 'body',
                    'parameters' => [
                        ['type' => 'text', 'text' => $m->cliente->nome],
                    ],
                ];
            }

            $res = $kapso->enviarTemplate(
                $empresa->kapso_sender_id,
                $m->cliente->telefone,
                $template->meta_id,
                $template->idioma,
                $components,
                $template->categoria === 'marketing',
            );

            $m->update([
                'status' => 'enviada',
                'provider_message_id' => $res['messages'][0]['id'] ?? null,
                'payload' => $res,
                'enviada_em' => now(),
            ]);
        } catch (\Throwable $e) {
            $m->update(['status' => 'falhou', 'erro' => $e->getMessage()]);
        }
    }
}
