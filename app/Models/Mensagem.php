<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mensagem extends Model
{
    protected $table = 'mensagens';

    protected $fillable = [
        'remetente_id',
        'titulo',
        'conteudo',
    ];

    public function remetente()
    {
        return $this->belongsTo(Usuario::class, 'remetente_id');
    }

    public function destinatarios()
    {
        return $this->hasMany(MensagemDestinatario::class);
    }
}
