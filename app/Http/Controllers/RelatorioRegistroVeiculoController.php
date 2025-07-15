<?php

namespace App\Http\Controllers;

use App\Models\RegistroVeiculo;
use App\Models\Estacionamento;
use App\Models\Usuario;
use App\Models\Motorista;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RelatorioRegistroVeiculoController extends Controller
{
    public function index(Request $request)
    {
        $localizacoes = Estacionamento::select('localizacao')->distinct()->get();

        $motoristasEntrada = Motorista::whereIn('id', RegistroVeiculo::select('motorista_entrada_id')->distinct())->pluck('nome');
        $motoristasSaida = Motorista::whereIn('id', RegistroVeiculo::select('motorista_saida_id')->distinct())->pluck('nome');

        $usuariosEntrada = Usuario::whereIn('id', RegistroVeiculo::select('usuario_entrada_id')->distinct())->get();
        $usuariosSaida = Usuario::whereIn('id', RegistroVeiculo::select('usuario_saida_id')->distinct())->get();

        $query = RegistroVeiculo::with([
            'veiculo.acesso',
            'motoristaEntrada',
            'motoristaSaida',
            'usuarioEntrada',
            'usuarioSaida',
            'estacionamento',
        ]);

        // Filtros
        if ($request->filled('placa')) {
            $query->where('placa', 'like', '%' . $request->placa . '%');
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('data_inicial')) {
            try {
                $data = $request->data_inicial;
                $hora = $request->hora_inicial ?? '00:00';
                $inicio = Carbon::createFromFormat('Y-m-d H:i', "$data $hora");
                $query->where('horario_entrada', '>=', $inicio);
            } catch (\Exception $e) {
                Log::error('Erro ao processar data_inicial: ' . $e->getMessage());
            }
        }

        if ($request->filled('data_final')) {
            try {
                $data = $request->data_final;
                $hora = $request->hora_final ?? '23:59';
                $fim = Carbon::createFromFormat('Y-m-d H:i', "$data $hora");
                $query->where('horario_saida', '<=', $fim);
            } catch (\Exception $e) {
                Log::error('Erro ao processar data_final: ' . $e->getMessage());
            }
        }

        if ($request->filled('localizacao')) {
            $query->whereHas('estacionamento', function ($q) use ($request) {
                $q->where('localizacao', $request->localizacao);
            });
        }

        if ($request->filled('nome_motorista_entrada')) {
            $query->whereHas('motoristaEntrada', function ($q) use ($request) {
                $q->where('nome', $request->nome_motorista_entrada);
            });
        }

        if ($request->filled('nome_motorista_saida')) {
            $query->whereHas('motoristaSaida', function ($q) use ($request) {
                $q->where('nome', $request->nome_motorista_saida);
            });
        }

        if ($request->filled('usuario_entrada')) {
            $query->whereHas('usuarioEntrada', function ($q) use ($request) {
                $q->where('nome', $request->usuario_entrada);
            });
        }

        if ($request->filled('usuario_saida')) {
            $query->whereHas('usuarioSaida', function ($q) use ($request) {
                $q->where('nome', $request->usuario_saida);
            });
        }

        $registros = $query->orderByDesc('horario_entrada')->paginate(10);

        return view('relatorios.registro_veiculos.index', compact(
            'registros',
            'localizacoes',
            'motoristasEntrada',
            'motoristasSaida',
            'usuariosEntrada',
            'usuariosSaida'
        ));
    }

    public function exportar(Request $request)
    {
        $query = RegistroVeiculo::with([
            'veiculo.acesso',
            'motoristaEntrada',
            'motoristaSaida',
            'usuarioEntrada',
            'usuarioSaida',
            'estacionamento',
        ]);

        if ($request->filled('placa')) {
            $query->where('placa', 'like', '%' . $request->placa . '%');
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('data_inicial')) {
            try {
                $data = $request->data_inicial;
                $hora = $request->hora_inicial ?? '00:00';
                $inicio = Carbon::createFromFormat('Y-m-d H:i', "$data $hora");
                $query->where('horario_entrada', '>=', $inicio);
            } catch (\Exception $e) {
                Log::error('Erro ao processar data_inicial (exportar): ' . $e->getMessage());
            }
        }

        if ($request->filled('data_final')) {
            try {
                $data = $request->data_final;
                $hora = $request->hora_final ?? '23:59';
                $fim = Carbon::createFromFormat('Y-m-d H:i', "$data $hora");
                $query->where('horario_saida', '<=', $fim);
            } catch (\Exception $e) {
                Log::error('Erro ao processar data_final (exportar): ' . $e->getMessage());
            }
        }

        if ($request->filled('localizacao')) {
            $query->whereHas('estacionamento', function ($q) use ($request) {
                $q->where('localizacao', $request->localizacao);
            });
        }

        if ($request->filled('nome_motorista_entrada')) {
            $query->whereHas('motoristaEntrada', function ($q) use ($request) {
                $q->where('nome', $request->nome_motorista_entrada);
            });
        }

        if ($request->filled('nome_motorista_saida')) {
            $query->whereHas('motoristaSaida', function ($q) use ($request) {
                $q->where('nome', $request->nome_motorista_saida);
            });
        }

        if ($request->filled('usuario_entrada')) {
            $query->whereHas('usuarioEntrada', function ($q) use ($request) {
                $q->where('nome', $request->usuario_entrada);
            });
        }

        if ($request->filled('usuario_saida')) {
            $query->whereHas('usuarioSaida', function ($q) use ($request) {
                $q->where('nome', $request->usuario_saida);
            });
        }

        $registros = $query->orderByDesc('horario_entrada')->get();

        $filtros = $request->only([
            'placa',
            'tipo',
            'localizacao',
            'nome_motorista_entrada',
            'nome_motorista_saida',
            'usuario_entrada',
            'usuario_saida',
            'data_inicial',
            'hora_inicial',
            'data_final',
            'hora_final'
        ]);

        $pdf = Pdf::loadView('relatorios.registro_veiculos.pdf', [
            'registros' => $registros,
            'filtros' => $filtros
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('relatorio_registro_veiculos.pdf');
    }
}
