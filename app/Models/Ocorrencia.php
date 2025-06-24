<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ocorrencia extends Model
{
    // Define a tabela explicitamente (opcional se seguir convenção)
    protected $table = 'ocorrencias';

    // Sem timestamps padrão (created_at / updated_at)
    public $timestamps = false;

    // Campos permitidos para preenchimento em massa
    protected $fillable = [        
        'ocorrencia',
        'horario',
        'usuario_id',
    ];

    // Cast para facilitar o uso do campo horario com Carbon
    protected $casts = [
        'horario' => 'datetime',
    ];

    /**
     * Relacionamento com o Usuário
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
