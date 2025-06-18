<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcessoLiberado extends Model
{
    // Ativa timestamps automáticos (porque as colunas existem no banco)
    public $timestamps = true;

    protected $table = 'acessos_liberados';

    protected $fillable = [
        'nome',
        'matricula',
    ];
}
