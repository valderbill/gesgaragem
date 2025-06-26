<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ocorrencia extends Model
{
    use HasFactory;

    protected $table = 'ocorrencias';

    public $timestamps = false;

    protected $fillable = [        
        'ocorrencia',
        'horario',
        'usuario_id',
    ];

    protected $casts = [
        'horario' => 'datetime',
    ];

    /**
     * Relacionamento: Ocorrência pertence a um usuário (model Usuario)
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /**
     * Relacionamento: Ocorrência tem muitos acompanhamentos
     */
    public function acompanhamentos()
    {
        return $this->hasMany(Acompanhamento::class, 'ocorrencia_id');
    }
}
