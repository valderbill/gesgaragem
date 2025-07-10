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
    ];

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

    // A função abaixo se tornou desnecessária
    // porque agora o nome vem direto de acessos_liberados.nome
    // public function usuarioVinculado() { ... }
}
