<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estacionamento;
use App\Models\RegistroVeiculo;

class PainelController extends Controller
{
    public function dados(Request $request)
    {
        // ID do estacionamento selecionado na sessão
        $estacionamentoId = session('estacionamento_id');

        if (!$estacionamentoId) {
            return response()->json([
                'error' => 'Estacionamento não selecionado'
            ], 400);
        }

        $estacionamento = Estacionamento::findOrFail($estacionamentoId);

        $tipos = ['OFICIAL', 'PARTICULAR', 'MOTO'];

        $colunasTotal = [
            'OFICIAL' => 'vagas_oficiais',
            'PARTICULAR' => 'vagas_particulares',
            'MOTO' => 'vagas_motos',
        ];

        $dados = [
            'total' => [],
            'ocupadas' => [],
        ];

        foreach ($tipos as $tipo) {
            $total = $estacionamento->{$colunasTotal[$tipo]} ?? 0;

            $ocupadas = RegistroVeiculo::where('tipo', $tipo)
                ->where('estacionamento_id', $estacionamentoId)
                ->whereNull('horario_saida')
                ->count();

            $dados['total'][$tipo] = $total;
            $dados['ocupadas'][$tipo] = $ocupadas;
        }

        return response()->json($dados);
    }
}
