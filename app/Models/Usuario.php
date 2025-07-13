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
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    /**
     * Relacionamento com o perfil do usuário
     */
    public function perfil()
    {
        return $this->belongsTo(Perfil::class);
    }

    /**
     * Define qual atributo o Laravel usa para autenticação
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Mutator para criptografar a senha automaticamente
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * 🔠 Mutator para salvar o nome sempre em MAIÚSCULAS
     */
    public function setNomeAttribute($value)
    {
        $this->attributes['nome'] = mb_strtoupper($value, 'UTF-8');
    }

    /**
     * Relacionamento: mensagens não lidas via tabela pivot
     */
    public function mensagensNaoLidas()
    {
        return $this->belongsToMany(Mensagem::class, 'mensagem_destinatarios', 'destinatario_id', 'mensagem_id')
                    ->wherePivot('lida', false);
    }

    /**
     * Relacionamento: acessos liberados
     */
    public function acessosLiberados()
    {
        return $this->hasMany(AcessoLiberado::class, 'usuario_id');
    }
}
