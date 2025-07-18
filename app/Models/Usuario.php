<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;

class Usuario extends Authenticatable
{
    use HasFactory;

    protected $table = 'usuarios';

    /**
     * Campos em massa.
     */
    protected $fillable = [
        'nome',
        'matricula',
        'senha',
        'perfil_id',
        'ativo',
        'criado_por',   
    ];

    /**
     * Campos ocultos em arrays/JSON.
     */
    protected $hidden = [
        'senha',
        'remember_token', // ok deixar; remova se não usa coluna
    ];

    /**
     * Casts de tipos.
     */
    protected $casts = [
        'ativo' => 'boolean',
    ];

    /*------------------------------------------------------------------
     | MUTATORS / ACCESSORS
     *-----------------------------------------------------------------*/

    /**
     * Usa o campo 'senha' (em vez de 'password') e garante hash.
     * Evita re-hash se o valor já parece estar hasheado.
     */
    public function setSenhaAttribute($value): void
    {
        if (empty($value)) {
            return;
        }

        // Se já está bcryptado (começa com $2y$ ou $2a$), aceita como está
        if (strpos($value, '$2y$') === 0 || strpos($value, '$2a$') === 0) {
            $this->attributes['senha'] = $value;
            return;
        }

        // Caso contrário, aplica hash
        $this->attributes['senha'] = Hash::make($value);
    }

    /**
     * Informa ao sistema de autenticação qual campo é a senha.
     */
    public function getAuthPassword()
    {
        return $this->senha;
    }

    /*------------------------------------------------------------------
     | RELACIONAMENTOS
     *-----------------------------------------------------------------*/

    /**
     * Perfil ao qual este usuário pertence.
     */
    public function perfil()
    {
        return $this->belongsTo(Perfil::class, 'perfil_id');
    }

    /**
     * Usuário que criou este registro (auditoria).
     * Campo: usuarios.criado_por → usuarios.id
     */
    public function criador()
    {
        return $this->belongsTo(self::class, 'criado_por');
    }

    /**
     * Mensagens enviadas por este usuário.
     */
    public function mensagensEnviadas()
    {
        return $this->hasMany(Mensagem::class, 'remetente_id');
    }

    /**
     * Mensagens recebidas por este usuário.
     */
    public function mensagensRecebidas()
    {
        return $this->hasMany(MensagemDestinatario::class, 'destinatario_id');
    }

    /**
     * Mensagens recebidas e ainda não lidas.
     */
    public function mensagensNaoLidas()
    {
        return $this->mensagensRecebidas()->where('lida', false);
    }
}
