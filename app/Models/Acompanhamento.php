<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Acompanhamento extends Model
{
    use HasFactory;

    protected $table = 'acompanhamentos';

    protected $fillable = [
        'ocorrencia_id',
        'descricao',
        'horario',
        'usuario_id',
    ];

    public function ocorrencia()
    {
        return $this->belongsTo(Ocorrencia::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
