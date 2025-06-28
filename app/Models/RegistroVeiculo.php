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

    public function motoristaEntrada()
    {
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
}
