<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class RegistroVeiculo extends Model
{
        public $timestamps = false;

    protected $fillable = [
        'veiculo_id',
        'placa',
        'marca',
        'modelo',
        'cor',
        'tipo', // OFICIAL | PARTICULAR | MOTO
        'motorista_entrada_id',        // FK -> motoristas_oficiais.id OU (para part/moto) id de acesso
        'motorista_saida_id',          // FK -> motoristas_oficiais.id (OFICIAL)
        'motorista_saida_outros_id',   // FK -> acessos_liberados.id (PARTICULAR/MOTO) 
        'horario_entrada',
        'horario_saida',
        'usuario_entrada_id',
        'usuario_saida_id',
        'estacionamento_id',
        'quantidade_passageiros',
    ];

    protected $casts = [
        'horario_entrada' => 'datetime',
        'horario_saida'   => 'datetime',
        'quantidade_passageiros' => 'integer',
    ];

    public function veiculo(): BelongsTo
    {
        return $this->belongsTo(Veiculo::class, 'veiculo_id');
    }

    /**
     * Motorista oficial (entrada).
     */
    public function motoristaEntrada(): BelongsTo
    {
        return $this->belongsTo(Motorista::class, 'motorista_entrada_id');
    }

    /**
     * Motorista oficial (saída). Usado apenas em OFICIAL.
     */
    public function motoristaSaida(): BelongsTo
    {
        return $this->belongsTo(Motorista::class, 'motorista_saida_id');
    }

    /**
     * Motorista não-oficial usado na saída (PARTICULAR/MOTO) — Acesso Liberado.
     */
    public function motoristaSaidaOutros(): BelongsTo
    {
        return $this->belongsTo(AcessoLiberado::class, 'motorista_saida_outros_id');
    }

    /**
     * Usuário que registrou a ENTRADA.
     */
    public function usuarioEntrada(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_entrada_id');
    }

    /**
     * Usuário que registrou a SAÍDA.
     */
    public function usuarioSaida(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_saida_id');
    }

    /**
     * Estacionamento onde ocorreu o registro.
     */
    public function estacionamento(): BelongsTo
    {
        return $this->belongsTo(Estacionamento::class, 'estacionamento_id');
    }

    public function getNomeMotoristaEntradaAttribute(): ?string
    {
        if ($this->tipo === 'OFICIAL') {
            return optional($this->motoristaEntrada)->nome;
        }

        // Particular / Moto: motorista vem do acesso liberado vinculado ao veículo
        return optional(optional($this->veiculo)->acesso)->nome;
    }

    public function getNomeMotoristaSaidaAttribute(): ?string
    {
        // Se houver motorista de saída "outros" (AcessoLiberado) 
        if ($this->motorista_saida_outros_id && $this->relationLoaded('motoristaSaidaOutros')) {
            if ($this->motoristaSaidaOutros) {
                return $this->motoristaSaidaOutros->nome;
            }
        }

        if ($this->tipo === 'OFICIAL') {
            return optional($this->motoristaSaida)->nome;
        }
        
        return $this->nome_motorista_entrada; 
    }

    /* -----------------------------------------------------------------
     | Data formatada
     |------------------------------------------------------------------
     */

    public function getHorarioEntradaFormatadoAttribute(): string
    {
        $dt = $this->horario_entrada instanceof Carbon
            ? $this->horario_entrada
            : ($this->horario_entrada ? Carbon::parse($this->horario_entrada) : null);

        return $dt ? $dt->format('d/m/Y H:i') : '-';
    }

    public function getHorarioSaidaFormatadoAttribute(): string
    {
        $dt = $this->horario_saida instanceof Carbon
            ? $this->horario_saida
            : ($this->horario_saida ? Carbon::parse($this->horario_saida) : null);

        return $dt ? $dt->format('d/m/Y H:i') : '-';
    }
}
