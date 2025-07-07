<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Veiculo extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'placa',
        'modelo',
        'cor',
        'tipo',
        'marca',
        'acesso_id', // campo de relacionamento com AcessoLiberado
    ];

    /**
     * Relacionamento com AcessoLiberado (acesso_id está na tabela veiculos).
     * Um veículo pertence a um acesso liberado.
     */
    public function acessoLiberado()
    {
        return $this->belongsTo(AcessoLiberado::class, 'acesso_id');
    }

    public function buscar(Request $request)
{
    $term = $request->get('term');

    $veiculos = Veiculo::where('placa', 'ILIKE', "%{$term}%")
        ->select('id', DB::raw("placa AS text"))
        ->limit(10)
        ->get();

    return response()->json($veiculos);
}

}
