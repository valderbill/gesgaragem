<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estacionamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'localizacao',         
        'vagas_particulares',
        'vagas_oficiais',
        'vagas_motos',
    ];
}
 