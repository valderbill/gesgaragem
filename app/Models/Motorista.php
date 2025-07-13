<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Motorista extends Model
{
    // Define a tabela associada (caso o nome não siga a convenção "motoristas")
    protected $table = 'motoristas_oficiais';

    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'nome',
        'matricula',
        'foto',
    ];

    /**
     * 🔠 Mutator: converte o nome para MAIÚSCULAS automaticamente
     */
    public function setNomeAttribute($value)
    {
        $this->attributes['nome'] = mb_strtoupper($value, 'UTF-8');
    }
}
