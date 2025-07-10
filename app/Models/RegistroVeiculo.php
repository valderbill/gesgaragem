<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroVeiculo extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'veiculo_id',
        'placa',
        'marca',
        'modelo',
        'cor',
        'tipo',
        'motorista_entrada_id',
        'motorista_saida_id',
        'horario_entrada',
        'horario_saida',
        'usuario_saida_id',
        'usuario_entrada_id',
        'estacionamento_id',
        'quantidade_passageiros',
    ];

    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class, 'veiculo_id');
    }

    // Relacionamento com motoristas oficiais (para veículos OFICIAIS)
    public function motoristaOficialEntrada()
    {
        return $this->belongsTo(Motorista::class, 'motorista_entrada_id');
    }

    // Relacionamento com usuários (para veículos PARTICULAR ou MOTO com acesso liberado)
    public function motoristaUsuarioEntrada()
    {
        return $this->belongsTo(Usuario::class, 'motorista_entrada_id');
    }

    // Relação genérica que evita erro no controller
    public function motoristaEntrada()
    {
        // Retorna o mesmo que motoristaOficialEntrada, por compatibilidade
        return $this->belongsTo(Motorista::class, 'motorista_entrada_id');
    }

    public function motoristaSaida()
    {
        return $this->belongsTo(Motorista::class, 'motorista_saida_id');
    }

    public function usuarioEntrada()
    {
        return $this->belongsTo(Usuario::class, 'usuario_entrada_id');
    }

    public function usuarioSaida()
    {
        return $this->belongsTo(Usuario::class, 'usuario_saida_id');
    }

    public function estacionamento()
    {
        return $this->belongsTo(Estacionamento::class, 'estacionamento_id');
    }

    // Helper para exibir o nome do motorista de entrada corretamente
    public function getNomeMotoristaEntradaAttribute()
    {
        if ($this->tipo === 'OFICIAL') {
            return optional($this->motoristaOficialEntrada)->nome;
        } else {
            return optional($this->veiculo->acesso)->nome;
        }
    }
}
