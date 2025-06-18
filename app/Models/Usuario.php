<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nome',
        'matricula',
        'senha',
        'perfil_id',
        'ativo',
    ];

    protected $hidden = [
        'senha',
        'remember_token',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function perfil()
    {
        return $this->belongsTo(Perfil::class);
    }

    // Laravel vai usar a coluna 'senha' para autenticar
    public function getAuthPassword()
    {
        return $this->senha;
    }

    // Permite usar 'password' no cadastro, salvando em 'senha'
    public function setPasswordAttribute($value)
    {
        $this->attributes['senha'] = bcrypt($value);
    }
}
