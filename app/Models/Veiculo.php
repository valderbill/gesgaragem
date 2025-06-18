<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Veiculo extends Model
{
    public $timestamps = false; // ← isso desativa os campos created_at e updated_at

    protected $fillable = [
        'placa',
        'modelo',
        'cor',
        'tipo',
        'marca',
        'acesso_liberado_id',
    ];

    public function acessoLiberado()
    {
        return $this->belongsTo(AcessoLiberado::class, 'acesso_liberado_id');
    }
}
