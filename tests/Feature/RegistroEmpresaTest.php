<?php

namespace Tests\Feature;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistroEmpresaTest extends TestCase
{
    use RefreshDatabase;

    public function test_pagina_de_registro_carrega(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_registro_cria_empresa_e_usuario(): void
    {
        $response = $this->post('/register', [
            'empresa_nome'          => 'Minha Loja',
            'name'                  => 'João Teste',
            'email'                 => 'joao@teste.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('empresas', ['nome' => 'Minha Loja']);
        $this->assertDatabaseHas('users', [
            'email' => 'joao@teste.com',
            'papel' => 'admin_empresa',
        ]);

        $user = User::where('email', 'joao@teste.com')->first();
        $this->assertNotNull($user->empresa_id);
        $this->assertEquals('Minha Loja', $user->empresa->nome);
    }

    public function test_registro_requer_nome_da_empresa(): void
    {
        $response = $this->post('/register', [
            'empresa_nome'          => '',
            'name'                  => 'João',
            'email'                 => 'joao@teste.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('empresa_nome');
        $this->assertDatabaseCount('empresas', 0);
    }

    public function test_login_funciona(): void
    {
        $empresa = Empresa::create(['nome' => 'Loja A']);
        User::create([
            'empresa_id' => $empresa->id,
            'name'       => 'Admin',
            'email'      => 'admin@loja.com',
            'password'   => bcrypt('password'),
            'papel'      => 'admin_empresa',
        ]);

        $response = $this->post('/login', [
            'email'    => 'admin@loja.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }
}
