<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Perfil;
use App\Models\Mensagem;
use App\Models\AcessoLiberado;

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
        // Campos que são definidos automaticamente mas podem ser preenchidos no controller
        'criado_por_id',
        'ativado_por_id',
        'data_ativacao',
        'inativado_por_id',
        'data_inativacao',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'data_ativacao' => 'datetime',
        'data_inativacao' => 'datetime',
    ];

    /**
     * 🔒 Senha criptografada
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * 🆙 Nome sempre em MAIÚSCULAS
     */
    public function setNomeAttribute($value)
    {
        $this->attributes['nome'] = mb_strtoupper($value, 'UTF-8');
    }

    /**
     * 🔐 Perfil do usuário
     */
    public function perfil()
    {
        return $this->belongsTo(Perfil::class);
    }

    /**
     * 📨 Mensagens não lidas (relacionamento com pivot)
     */
    public function mensagensNaoLidas()
    {
        return $this->belongsToMany(Mensagem::class, 'mensagem_destinatarios', 'destinatario_id', 'mensagem_id')
                    ->wherePivot('lida', false);
    }

    /**
     * ✅ Acessos liberados
     */
    public function acessosLiberados()
    {
        return $this->hasMany(AcessoLiberado::class, 'usuario_id');
    }

    /**
     * 👤 Usuário que criou este usuário
     */
    public function criador()
    {
        return $this->belongsTo(self::class, 'criado_por_id');
    }

    /**
     * ✅ Usuário que ativou este usuário
     */
    public function ativadoPor()
    {
        return $this->belongsTo(self::class, 'ativado_por_id');
    }

    /**
     * 🚫 Usuário que inativou este usuário
     */
    public function inativadoPor()
    {
        return $this->belongsTo(self::class, 'inativado_por_id');
    }

    /**
     * 🔑 Autenticação: qual campo usar como senha
     */
    public function getAuthPassword()
    {
        return $this->password;
    }
}
