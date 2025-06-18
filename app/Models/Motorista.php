<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Motorista extends Model
{
    protected $table = 'motoristas_oficiais'; // aponta para a tabela 

    protected $fillable = [
        'nome',
        'matricula',
        'foto',
    ];
}
