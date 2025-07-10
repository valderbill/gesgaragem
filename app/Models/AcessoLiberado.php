<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcessoLiberado extends Model
{
    public $timestamps = true;

    protected $table = 'acessos_liberados';

    protected $fillable = [
        'nome',
        'matricula',
        'motorista_id',
        'usuario_id', // <- Importante garantir que esse campo exista na tabela!
        'status',
    ];

    /**
     * (Opcional) Relacionamento com Motorista oficial (caso exista vínculo)
     */
    public function motorista()
    {
        return $this->belongsTo(\App\Models\Motorista::class, 'motorista_id');
    }

    /**
     * Relacionamento com Usuário (motorista para veículos PARTICULAR/MOTO)
     */
    public function usuario()
    {
        return $this->belongsTo(\App\Models\Usuario::class, 'usuario_id');
    }

    /**
     * Relacionamento com veículos vinculados a este acesso (PARTICULAR/MOTO)
     */
    public function veiculos()
    {
        return $this->hasMany(\App\Models\Veiculo::class, 'acesso_id');
    }
}
