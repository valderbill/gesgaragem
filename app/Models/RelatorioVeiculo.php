<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Usuario; // Ajuste para seu model de usuários

class RelatorioVeiculo extends Model
{
    protected $table = 'relatorios_veiculos';

    protected $fillable = [
        'nome',
        'tipos',    // armazenado como JSON
        'user_id',  // id do usuário que criou o relatório
    ];

    protected $casts = [
        'tipos' => 'array',
    ];

    // Relacionamento com o usuário criador do relatório
    public function user()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }
}
