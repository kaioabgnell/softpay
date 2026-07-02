<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'documento', 'whatsapp_numero', 'kapso_sender_id', 'ativo'];

    protected $casts = ['ativo' => 'boolean'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function templates()
    {
        return $this->hasMany(Template::class);
    }

    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }

    public function mensagens()
    {
        return $this->hasMany(Mensagem::class);
    }
}
