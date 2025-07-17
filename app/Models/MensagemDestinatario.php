<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MensagemDestinatario extends Model
{
    protected $table = 'mensagem_destinatarios';

    protected $fillable = [
        'mensagem_id',
        'destinatario_id',
        'lida',
        'data_leitura',
    ];

    public function mensagem()
    {
        return $this->belongsTo(Mensagem::class);
    }

    public function destinatario()
    {
        return $this->belongsTo(Usuario::class, 'destinatario_id');
    }
}
