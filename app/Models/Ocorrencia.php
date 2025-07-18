<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Usuario;
use App\Models\Acompanhamento;

class Ocorrencia extends Model
{
    use HasFactory;

    // Nome da tabela no banco de dados
    protected $table = 'ocorrencias';

    // Desativa os campos automáticos de timestamps (created_at, updated_at)
    public $timestamps = false;

    // Campos que podem ser preenchidos em massa (mass assignment)
    protected $fillable = ['descricao', 'horario', 'usuario_id'];  

    // Conversão automática de tipos
    protected $casts = [
        'horario' => 'datetime',
    ];

    /**
     * Relacionamento: Ocorrência pertence a um usuário (modelo Usuario)
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /**
     * Relacionamento: Ocorrência pode ter muitos acompanhamentos
     */
    public function acompanhamentos()
    {
        return $this->hasMany(Acompanhamento::class, 'ocorrencia_id');
    }
}
