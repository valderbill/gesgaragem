<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Ocorrencia;
use App\Models\Usuario;

class Acompanhamento extends Model
{
    use HasFactory;

    // Nome da tabela no banco de dados
    protected $table = 'acompanhamentos';

    // Campos que podem ser preenchidos em massa (mass assignment)
    protected $fillable = [
        'ocorrencia_id',
        'descricao',
        'horario',
        'usuario_id',
    ];

    // Conversão automática de tipos
    protected $casts = [
        'horario' => 'datetime',
    ];

    /**
     * Relacionamento: Acompanhamento pertence a uma ocorrência
     */
    public function ocorrencia()
    {
        return $this->belongsTo(Ocorrencia::class, 'ocorrencia_id');
    }

    /**
     * Relacionamento: Acompanhamento pertence a um usuário
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
