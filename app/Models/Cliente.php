<?php

namespace App\Models;

use App\Models\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = ['empresa_id', 'nome', 'telefone', 'opt_out'];

    protected $casts = ['opt_out' => 'boolean'];

    protected static function booted(): void
    {
        static::addGlobalScope(new EmpresaScope());
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function mensagens()
    {
        return $this->hasMany(Mensagem::class);
    }
}
