<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estacionamento;
use App\Models\RegistroVeiculo;
use Illuminate\Support\Facades\Log;

class PainelController extends Controller
{
    /**
     * Retorna dados em JSON para alimentar os cards e o gráfico
     * (total de vagas x ocupadas por tipo) do estacionamento selecionado.
     */
    public function dados(Request $request)
    {
        // Recupera o estacionamento ativo da sessão
        $estacionamentoId = session('estacionamento_id');

        if (!$estacionamentoId) {
            // Log para diagnóstico
            Log::warning('PainelController@dados chamado sem estacionamento_id na sessão.');
            return response()->json([
                'error' => 'Nenhum estacionamento selecionado na sessão.'
            ], 400);
        }

        $estacionamento = Estacionamento::find($estacionamentoId);
        if (!$estacionamento) {
            Log::warning("PainelController@dados: estacionamento {$estacionamentoId} não encontrado.");
            return response()->json([
                'error' => 'Estacionamento não encontrado.'
            ], 404);
        }

        // Mapeamento tipo → coluna na tabela
        $tipos = ['OFICIAL', 'PARTICULAR', 'MOTO'];
        $colunasTotal = [
            'OFICIAL'     => 'vagas_oficiais',
            'PARTICULAR'  => 'vagas_particulares',
            'MOTO'        => 'vagas_motos',
        ];

        $dados = [
            'estacionamento_id'   => $estacionamento->id,
            'estacionamento_nome' => $estacionamento->localizacao,
            'total'               => [],
            'ocupadas'            => [],
        ];

        foreach ($tipos as $tipo) {
            $col = $colunasTotal[$tipo];

            // Total configurado no cadastro do estacionamento
            $total = (int) ($estacionamento->$col ?? 0);

            // Ocupadas = registros sem horário de saída no estacionamento ativo
            $ocupadas = RegistroVeiculo::where('tipo', $tipo)
                ->where('estacionamento_id', $estacionamento->id)
                ->whereNull('horario_saida')
                ->count();

            $dados['total'][$tipo]    = $total;
            $dados['ocupadas'][$tipo] = $ocupadas;
        }

        return response()->json($dados);
    }
}
