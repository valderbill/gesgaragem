<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Veiculo extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'placa',
        'modelo',
        'cor',
        'tipo',
        'marca',
        'acesso_id',      // Para veículos PARTICULAR/MOTO
        'motorista_id',   // Para veículos OFICIAL
        // Adicione aqui outros campos que existirem na tabela veiculos
    ];

    /**
     * Relacionamento com AcessoLiberado (usado para veículos PARTICULAR/MOTO).
     */
    public function acesso()
    {
        return $this->belongsTo(\App\Models\AcessoLiberado::class, 'acesso_id');
    }

    /**
     * Relacionamento com MotoristaOficial (usado para veículos OFICIAL).
     */
    public function motoristaOficial()
    {
        return $this->belongsTo(\App\Models\MotoristaOficial::class, 'motorista_id');
    }

    // Exemplo de outros relacionamentos, caso existam:
    // public function usuario()
    // {
    //     return $this->belongsTo(\App\Models\Usuario::class, 'usuario_id');
    // }
}