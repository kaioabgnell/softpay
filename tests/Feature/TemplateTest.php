<?php

namespace Tests\Feature;

use App\Models\Empresa;
use App\Models\Template;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TemplateTest extends TestCase
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

    public function test_listar_templates(): void
    {
        Template::create([
            'empresa_id'      => $this->empresa->id,
            'nome'            => 'Boas Vindas',
            'meta_id'         => 'boas_vindas_v1',
            'usa_client_name' => true,
        ]);

        $response = $this->actingAs($this->user)->get('/templates');

        $response->assertStatus(200);
        $response->assertSee('Boas Vindas');
        $response->assertSee('boas_vindas_v1');
    }

    public function test_criar_template(): void
    {
        $response = $this->actingAs($this->user)->post('/templates', [
            'nome'            => 'Promoção',
            'meta_id'         => 'promocao_v1',
            'usa_client_name' => '1',
            'categoria'       => 'marketing',
        ]);

        $response->assertRedirect('/templates');
        $this->assertDatabaseHas('templates', [
            'nome'            => 'Promoção',
            'meta_id'         => 'promocao_v1',
            'empresa_id'      => $this->empresa->id,
            'usa_client_name' => 1,
        ]);
    }

    public function test_criar_template_valida_campos(): void
    {
        $response = $this->actingAs($this->user)->post('/templates', [
            'nome'    => '',
            'meta_id' => '',
        ]);

        $response->assertSessionHasErrors(['nome', 'meta_id']);
    }

    public function test_editar_template(): void
    {
        $template = Template::create([
            'empresa_id'      => $this->empresa->id,
            'nome'            => 'Original',
            'meta_id'         => 'original_v1',
            'usa_client_name' => false,
        ]);

        $response = $this->actingAs($this->user)->put("/templates/{$template->id}", [
            'nome'            => 'Atualizado',
            'meta_id'         => 'atualizado_v2',
            'usa_client_name' => '0',
        ]);

        $response->assertRedirect('/templates');
        $this->assertDatabaseHas('templates', ['id' => $template->id, 'nome' => 'Atualizado']);
    }

    public function test_deletar_template(): void
    {
        $template = Template::create([
            'empresa_id'      => $this->empresa->id,
            'nome'            => 'A Deletar',
            'meta_id'         => 'deletar_v1',
            'usa_client_name' => false,
        ]);

        $response = $this->actingAs($this->user)->delete("/templates/{$template->id}");

        $response->assertRedirect('/templates');
        $this->assertDatabaseMissing('templates', ['id' => $template->id]);
    }
}
