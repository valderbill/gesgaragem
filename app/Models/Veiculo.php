<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class Veiculo extends Model
{
    // Desativa timestamps automáticos (created_at e updated_at)
    public $timestamps = false;

    protected $fillable = [
        'placa',
        'modelo',
        'cor',
        'tipo',
        'marca',
        'acesso_id',
        'motorista_id',
        'criado_por',
        'criado_em',
    ];

    /**
     * Define eventos para setar criador e data de criação automaticamente
     */
    protected static function booted()
    {
        static::creating(function ($veiculo) {
            if (Auth::check()) {
                $veiculo->criado_por = Auth::id();
            }

            $veiculo->criado_em = Carbon::now();
        });
    }

    /**
     * Relacionamento com AcessoLiberado (PARTICULAR/MOTO)
     */
    public function acesso()
    {
        return $this->belongsTo(AcessoLiberado::class, 'acesso_id');
    }

    /**
     * Relacionamento com Motorista Oficial (OFICIAL)
     */
    public function motoristaOficial()
    {
        return $this->belongsTo(Motorista::class, 'motorista_id');
    }

    /**
     * Relacionamento com o usuário que criou o veículo
     */
    public function criador()
    {
        return $this->belongsTo(\App\Models\Usuario::class, 'criado_por');
    }
}
