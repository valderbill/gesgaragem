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
        'acesso_id', // corrigido para bater com o controller e formulário
    ];

    public function acessoLiberado()
    {
        return $this->belongsTo(AcessoLiberado::class, 'acesso_id');
    }
}
