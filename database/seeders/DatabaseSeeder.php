<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\Template;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Empresa de demonstração
        $empresa = Empresa::create([
            'nome'     => 'Demo Store',
            'documento' => '00.000.000/0001-00',
            'ativo'    => true,
        ]);

        // Usuário admin de teste
        User::create([
            'empresa_id' => $empresa->id,
            'name'       => 'Admin Teste',
            'email'      => 'admin@softpay.test',
            'password'   => Hash::make('password'),
            'papel'      => 'admin_empresa',
        ]);

        // Templates de exemplo
        Template::create([
            'empresa_id'      => $empresa->id,
            'nome'            => 'Boas-vindas',
            'meta_id'         => 'boasvindas_v1',
            'usa_client_name' => true,
            'categoria'       => 'marketing',
        ]);

        Template::create([
            'empresa_id'      => $empresa->id,
            'nome'            => 'Cobrança Vencida',
            'meta_id'         => 'cobranca_vencida_v1',
            'usa_client_name' => false,
            'categoria'       => 'utility',
        ]);

        // Clientes de exemplo
        Cliente::create([
            'empresa_id' => $empresa->id,
            'nome'       => 'João da Silva',
            'telefone'   => '+5511999990001',
            'opt_out'    => false,
        ]);

        Cliente::create([
            'empresa_id' => $empresa->id,
            'nome'       => 'Maria Souza',
            'telefone'   => '+5562988880002',
            'opt_out'    => false,
        ]);

        Cliente::create([
            'empresa_id' => $empresa->id,
            'nome'       => 'Carlos Opt-Out',
            'telefone'   => '+5521977770003',
            'opt_out'    => true,
        ]);
    }
}
