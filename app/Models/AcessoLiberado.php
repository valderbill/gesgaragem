<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcessoLiberado extends Model
{
    public $timestamps = true;

    protected $table = 'acessos_liberados';

    protected $fillable = [
        'nome',
        'matricula',
        'motorista_id' // ← adicione aqui se quiser preencher via mass assignment
    ];

    public function motorista()
    {
        return $this->belongsTo(Motorista::class, 'motorista_id');
    }
}
