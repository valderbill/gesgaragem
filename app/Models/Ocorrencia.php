<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ocorrencia extends Model
{
    protected $table = 'ocorrencias';

    public $timestamps = false; // pois a tabela não tem created_at e updated_at

    protected $fillable = [
        'placa',
        'ocorrencia',
        'horario',
        'usuario_id',
    ];

    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class, 'placa', 'placa');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
