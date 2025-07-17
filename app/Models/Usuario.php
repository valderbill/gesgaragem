<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;

class Usuario extends Authenticatable
{
    use HasFactory;

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

    // Usa o campo 'senha' no lugar do padrão 'password'
    public function setSenhaAttribute($value)
    {
        $this->attributes['senha'] = Hash::make($value);
    }

    // Relacionamento com o modelo Perfil
    public function perfil()
    {
        return $this->belongsTo(Perfil::class);
    }

    // Mensagens enviadas
    public function mensagensEnviadas()
    {
        return $this->hasMany(Mensagem::class, 'remetente_id');
    }

    // Mensagens recebidas
    public function mensagensRecebidas()
    {
        return $this->hasMany(MensagemDestinatario::class, 'destinatario_id');
    }

    // Mensagens não lidas
    public function mensagensNaoLidas()
    {
        return $this->mensagensRecebidas()->where('lida', false);
    }

    // Campo de senha personalizado
    public function getAuthPassword()
    {
        return $this->senha;
    }
}
