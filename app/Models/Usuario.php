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
        'password',
        'perfil_id',
        'ativo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function perfil()
    {
        return $this->belongsTo(Perfil::class);
    }

    // Laravel vai usar a coluna 'password' para autenticar
    public function getAuthPassword()
    {
        return $this->password;
    }

    // Permite usar 'password' no cadastro, salvando em 'password'
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
}
