<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClienteTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Empresa $empresa;

    protected function setUp(): void
    {
        parent::setUp();

        $this->empresa = Empresa::create(['nome' => 'Loja Teste']);
        $this->user    = User::create([
            'empresa_id' => $this->empresa->id,
            'name'       => 'Admin',
            'email'      => 'admin@teste.com',
            'password'   => bcrypt('password'),
            'papel'      => 'admin_empresa',
        ]);
    }

    public function test_listar_clientes_requer_autenticacao(): void
    {
        $this->get('/clientes')->assertRedirect('/login');
    }

    public function test_listar_clientes(): void
    {
        Cliente::create(['empresa_id' => $this->empresa->id, 'nome' => 'Cliente A', 'telefone' => '+5511999990001']);
        Cliente::create(['empresa_id' => $this->empresa->id, 'nome' => 'Cliente B', 'telefone' => '+5511999990002']);

        $response = $this->actingAs($this->user)->get('/clientes');

        $response->assertStatus(200);
        $response->assertSee('Cliente A');
        $response->assertSee('Cliente B');
    }

    public function test_criar_cliente(): void
    {
        $response = $this->actingAs($this->user)->post('/clientes', [
            'nome'     => 'Novo Cliente',
            'telefone' => '+5562999990001',
        ]);

        $response->assertRedirect('/clientes');
        $this->assertDatabaseHas('clientes', [
            'nome'       => 'Novo Cliente',
            'telefone'   => '+5562999990001',
            'empresa_id' => $this->empresa->id,
        ]);
    }

    public function test_criar_cliente_valida_campos_obrigatorios(): void
    {
        $response = $this->actingAs($this->user)->post('/clientes', [
            'nome'     => '',
            'telefone' => '',
        ]);

        $response->assertSessionHasErrors(['nome', 'telefone']);
        $this->assertDatabaseCount('clientes', 0);
    }

    public function test_editar_cliente(): void
    {
        $cliente = Cliente::create([
            'empresa_id' => $this->empresa->id,
            'nome'       => 'Original',
            'telefone'   => '+5511999990001',
        ]);

        $response = $this->actingAs($this->user)->put("/clientes/{$cliente->id}", [
            'nome'     => 'Editado',
            'telefone' => '+5511999990001',
            'opt_out'  => false,
        ]);

        $response->assertRedirect('/clientes');
        $this->assertDatabaseHas('clientes', ['id' => $cliente->id, 'nome' => 'Editado']);
    }

    public function test_deletar_cliente(): void
    {
        $cliente = Cliente::create([
            'empresa_id' => $this->empresa->id,
            'nome'       => 'A Deletar',
            'telefone'   => '+5511999990001',
        ]);

        $response = $this->actingAs($this->user)->delete("/clientes/{$cliente->id}");

        $response->assertRedirect('/clientes');
        $this->assertDatabaseMissing('clientes', ['id' => $cliente->id]);
    }

    public function test_isolamento_multi_tenant(): void
    {
        $outraEmpresa = Empresa::create(['nome' => 'Outra Loja']);
        Cliente::create([
            'empresa_id' => $outraEmpresa->id,
            'nome'       => 'Cliente de Outra Empresa',
            'telefone'   => '+5511000000000',
        ]);

        $response = $this->actingAs($this->user)->get('/clientes');

        $response->assertStatus(200);
        $response->assertDontSee('Cliente de Outra Empresa');
    }
}
