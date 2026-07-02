<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class KapsoService
{
    /**
     * Send a template message via Kapso's Meta Proxy API.
     *
     * Meta only allows the marketing_messages endpoint for templates whose
     * approved category is MARKETING; utility/authentication templates must
     * go through the regular messages endpoint or Meta rejects them with
     * "(#134100) Only marketing messages supported".
     *
     * @see https://docs.kapso.ai/api/meta/whatsapp/messages/send-a-message
     * @see https://docs.kapso.ai/api/meta/whatsapp/messages/send-a-marketing-message
     */
    public function enviarTemplate(string $phoneNumberId, string $telefone, string $templateName, string $languageCode, array $components = [], bool $marketing = false): array
    {
        $template = [
            'name' => $templateName,
            'language' => ['code' => $languageCode],
        ];

        if (!empty($components)) {
            $template['components'] = $components;
        }

        $endpoint = $marketing ? 'marketing_messages' : 'messages';

        $response = Http::withHeaders([
                'X-API-Key' => config('services.kapso.key'),
            ])
            ->baseUrl(config('services.kapso.url'))
            ->post("/{$phoneNumberId}/{$endpoint}", [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $telefone,
                'type' => 'template',
                'template' => $template,
            ]);

        $response->throw();

        return $response->json();
    }
}
