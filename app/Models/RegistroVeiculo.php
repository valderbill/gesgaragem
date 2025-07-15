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

    // Veículo relacionado
    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class, 'veiculo_id');
    }

    // Motorista oficial na entrada
    public function motoristaOficialEntrada()
    {
        return $this->belongsTo(Motorista::class, 'motorista_entrada_id');
    }

    // Motorista oficial na saída
    public function motoristaSaida()
    {
        return $this->belongsTo(Motorista::class, 'motorista_saida_id');
    }

    // Motorista genérico (usado em relatórios)
    public function motoristaEntrada()
    {
        return $this->belongsTo(Motorista::class, 'motorista_entrada_id');
    }

    // Usuário que realizou a entrada
    public function usuarioEntrada()
    {
        return $this->belongsTo(Usuario::class, 'usuario_entrada_id');
    }

    // Usuário que realizou a saída
    public function usuarioSaida()
    {
        return $this->belongsTo(Usuario::class, 'usuario_saida_id');
    }

    // Estacionamento relacionado
    public function estacionamento()
    {
        return $this->belongsTo(Estacionamento::class, 'estacionamento_id');
    }

    // Nome do motorista para exibição (usuado no relatório, adaptável para oficial ou particular)
    public function getNomeMotoristaEntradaAttribute()
    {
        if ($this->tipo === 'OFICIAL') {
            return optional($this->motoristaOficialEntrada)->nome;
        }

        // Caso seja veículo com acesso liberado (motorista particular registrado em "acesso")
        return optional(optional($this->veiculo)->acesso)->nome;
    }

    public function getNomeMotoristaSaidaAttribute()
    {
        return optional($this->motoristaSaida)->nome ?? '-';
    }

    // Formatação de datas para exibição segura
    public function getHorarioEntradaFormatadoAttribute()
    {
        return $this->horario_entrada ? date('d/m/Y H:i', strtotime($this->horario_entrada)) : '-';
    }

    public function getHorarioSaidaFormatadoAttribute()
    {
        return $this->horario_saida ? date('d/m/Y H:i', strtotime($this->horario_saida)) : '-';
    }
}
