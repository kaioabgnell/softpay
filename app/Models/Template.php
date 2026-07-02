<?php

namespace App\Models;

use App\Models\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected $fillable = ['empresa_id', 'nome', 'meta_id', 'idioma', 'usa_client_name', 'categoria'];

    protected $casts = ['usa_client_name' => 'boolean'];

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
