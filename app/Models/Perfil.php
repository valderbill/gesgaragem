<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    use HasFactory;

    protected $table = 'perfis';

    protected $fillable = ['nome'];

    // Relacionamento 1:n com Usuário
    public function usuarios()
    {
        return $this->hasMany(Usuario::class);
    }

    // Relacionamento n:n com Permissões
    public function permissoes()
    {
        return $this->belongsToMany(Permissao::class, 'perfil_permissoes', 'perfil_id', 'permissao_id');
    }
}
