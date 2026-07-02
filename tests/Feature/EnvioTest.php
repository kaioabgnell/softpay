<?php

namespace Tests\Feature;

use App\Jobs\EnviarMensagemJob;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Mensagem;
use App\Models\Template;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class EnvioTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Empresa $empresa;
    private Cliente $cliente;
    private Template $template;

    protected function setUp(): void
    {
        parent::setUp();

        $this->empresa  = Empresa::create(['nome' => 'Loja Teste']);
        $this->user     = User::create([
            'empresa_id' => $this->empresa->id,
            'name'       => 'Admin',
            'email'      => 'admin@teste.com',
            'password'   => bcrypt('password'),
            'papel'      => 'admin_empresa',
        ]);
        $this->cliente  = Cliente::create([
            'empresa_id' => $this->empresa->id,
            'nome'       => 'João',
            'telefone'   => '+5511999990001',
        ]);
        $this->template = Template::create([
            'empresa_id'      => $this->empresa->id,
            'nome'            => 'Boas Vindas',
            'meta_id'         => 'boas_vindas_v1',
            'usa_client_name' => true,
        ]);
    }

    public function test_envio_dispara_job_e_cria_mensagem(): void
    {
        Queue::fake();

        $response = $this->actingAs($this->user)->post("/clientes/{$this->cliente->id}/enviar", [
            'template_id' => $this->template->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('sucesso');

        Queue::assertPushed(EnviarMensagemJob::class);

        $this->assertDatabaseHas('mensagens', [
            'cliente_id'  => $this->cliente->id,
            'template_id' => $this->template->id,
            'empresa_id'  => $this->empresa->id,
            'status'      => 'pendente',
        ]);
    }

    public function test_envio_bloqueado_para_cliente_opt_out(): void
    {
        Queue::fake();

        $this->cliente->update(['opt_out' => true]);

        $response = $this->actingAs($this->user)->post("/clientes/{$this->cliente->id}/enviar", [
            'template_id' => $this->template->id,
        ]);

        $response->assertSessionHas('erro');
        Queue::assertNothingPushed();
        $this->assertDatabaseCount('mensagens', 0);
    }

    public function test_envio_requer_template_valido(): void
    {
        Queue::fake();

        $response = $this->actingAs($this->user)->post("/clientes/{$this->cliente->id}/enviar", [
            'template_id' => 99999,
        ]);

        $response->assertSessionHasErrors('template_id');
        Queue::assertNothingPushed();
    }
}
