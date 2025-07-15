<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Motorista extends Model
{
    // Define a tabela associada
    protected $table = 'motoristas_oficiais';

    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'nome',
        'matricula',
        'foto',
        'ativo',
    ];

    // Casts de tipo
    protected $casts = [
        'ativo' => 'boolean',
    ];

    /**
     * 🔠 Mutator: converte o nome para MAIÚSCULAS automaticamente
     */
    public function setNomeAttribute($value)
    {
        $this->attributes['nome'] = mb_strtoupper($value, 'UTF-8');
    }

    /**
     * 🔠 Mutator: converte a matrícula para MAIÚSCULAS automaticamente
     */
    public function setMatriculaAttribute($value)
    {
        $this->attributes['matricula'] = mb_strtoupper($value, 'UTF-8');
    }

    /**
     * ✅ Acessor: retorna texto legível para o status
     */
    public function getStatusTextoAttribute()
    {
        return $this->ativo ? 'ATIVO' : 'INATIVO';
    }

    /**
     * ✅ Acessor: retorna a URL da foto (caso seja usada no sistema)
     */
    public function getFotoUrlAttribute()
    {
        return asset('storage/fotos_motoristas/' . $this->foto);
    }
}
