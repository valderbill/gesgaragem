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
        'usuario_id',
        'status' // <-- novo campo para controle ativo/inativo
    ];

    // Relacionamento com Motorista
    public function motorista()
    {
        return $this->belongsTo(\App\Models\Usuario::class, 'motorista_id');
    }

    // Relacionamento com Usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
