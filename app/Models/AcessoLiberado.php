<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcessoLiberado extends Model
{
    public $timestamps = true;

    protected $table = 'acessos_liberados';

    protected $fillable = [
        'nome',
        'matricula',
        'motorista_id',
        'usuario_id',
        'status',
    ];

    // Relacionamento com o Usuário (Criador do Acesso)
    public function criador()
    {
        return $this->belongsTo(\App\Models\Usuario::class, 'usuario_id');
    }

    /**
     * 🔠 Mutator: Nome em MAIÚSCULAS
     */
    public function setNomeAttribute($value)
    {
        $this->attributes['nome'] = mb_strtoupper($value, 'UTF-8');
    }

    /**
     * 🔠 Mutator: Matrícula em MAIÚSCULAS (caso use letras)
     */
    public function setMatriculaAttribute($value)
    {
        $this->attributes['matricula'] = mb_strtoupper($value, 'UTF-8');
    }

    /**
     * Relacionamento com Motorista oficial
     */
    public function motorista()
    {
        return $this->belongsTo(\App\Models\Motorista::class, 'motorista_id');
    }

    /**
     * Relacionamento com Usuário (motorista para veículos PARTICULAR/MOTO)
     */
    public function usuario()
    {
        return $this->belongsTo(\App\Models\Usuario::class, 'usuario_id');
    }

    /**
     * Relacionamento com veículos vinculados a este acesso
     */
    public function veiculos()
    {
        return $this->hasMany(\App\Models\Veiculo::class, 'acesso_id');
    }
}
