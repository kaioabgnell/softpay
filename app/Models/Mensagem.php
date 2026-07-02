<?php

namespace App\Models;

use App\Models\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensagem extends Model
{
    use HasFactory;

    protected $table = 'mensagens';

    protected $fillable = [
        'empresa_id', 'cliente_id', 'template_id', 'status',
        'provider_message_id', 'payload', 'erro', 'enviada_em',
    ];

    protected $casts = [
        'payload'    => 'array',
        'enviada_em' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new EmpresaScope());
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }
}
