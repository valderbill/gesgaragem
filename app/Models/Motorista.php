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
}
