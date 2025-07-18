<?php

namespace App\Http\Controllers;

use App\Models\RegistroVeiculo;
use App\Models\Estacionamento;
use App\Models\Usuario;
use App\Models\Motorista; 
use App\Models\AcessoLiberado; 
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RelatorioRegistroVeiculoController extends Controller
{
    /**
     * Tela do relatório com filtros e paginação.
     */
    public function index(Request $request)
    {
       
        $localizacoes = Estacionamento::select('localizacao')->distinct()->orderBy('localizacao')->get();

        // Motoristas oficiais usados como entrada
        $motoristasEntrada = Motorista::whereIn(
            'id',
            RegistroVeiculo::select('motorista_entrada_id')->whereNotNull('motorista_entrada_id')->distinct()
        )->orderBy('nome')->pluck('nome', 'id');

        // Motoristas oficiais realmente registrados como saída
        $motoristasSaidaOficiais = Motorista::whereIn(
            'id',
            RegistroVeiculo::select('motorista_saida_id')->whereNotNull('motorista_saida_id')->distinct()
        )->orderBy('nome')->pluck('nome', 'id');

        // Motoristas de saída para PART/MOTO
        $motoristasSaidaPartMoto = RegistroVeiculo::query()
            ->whereIn('tipo', ['PARTICULAR', 'MOTO'])
            ->whereNotNull('motorista_entrada_id')
            ->with('motoristaEntrada:id,nome')
            ->get()
            ->pluck('motoristaEntrada.nome')
            ->filter()
            ->unique()
            ->values();

        // Junta ambos p/ usar no filtro dropdown de saída (coleção simples de nomes)
        $motoristasSaida = $motoristasSaidaOficiais->values()->merge($motoristasSaidaPartMoto)->unique()->sort()->values();

        // Usuários (entrada/saída)
        $usuariosEntrada = Usuario::whereIn(
            'id',
            RegistroVeiculo::select('usuario_entrada_id')->whereNotNull('usuario_entrada_id')->distinct()
        )->orderBy('nome')->get();

        $usuariosSaida = Usuario::whereIn(
            'id',
            RegistroVeiculo::select('usuario_saida_id')->whereNotNull('usuario_saida_id')->distinct()
        )->orderBy('nome')->get();

        $query = RegistroVeiculo::with([
            'veiculo.acesso',          // para PART/MOTO
            'motoristaEntrada',        // oficial ou usado como referência
            'motoristaSaida',          // oficial (saída real)
            'motoristaSaidaOutros',    // acessos_liberados (se mapeado no model)
            'usuarioEntrada',
            'usuarioSaida',
            'estacionamento',
        ]);

        $this->aplicarFiltros($query, $request);

        // Paginação
        $registros = $query
            ->orderByDesc('horario_entrada')
            ->paginate(10)
            ->withQueryString();

        // Adiciona campo calculado motorista_saida_relatorio na coleção paginada
        $registros->getCollection()->transform(function ($r) {
            $r->motorista_saida_relatorio = $this->resolverMotoristaSaida($r);
            return $r;
        });

        return view('relatorios.registro_veiculos.index', compact(
            'registros',
            'localizacoes',
            'motoristasEntrada',
            'motoristasSaida',
            'usuariosEntrada',
            'usuariosSaida'
        ));
    }

    /**
     * Gera PDF com os mesmos filtros da tela.
     */
    public function exportar(Request $request)
    {
        $query = RegistroVeiculo::with([
            'veiculo.acesso',
            'motoristaEntrada',
            'motoristaSaida',
            'motoristaSaidaOutros',
            'usuarioEntrada',
            'usuarioSaida',
            'estacionamento',
        ]);

        $this->aplicarFiltros($query, $request);

        $registros = $query
            ->orderByDesc('horario_entrada')
            ->get()
            ->map(function ($r) {
                $r->motorista_saida_relatorio = $this->resolverMotoristaSaida($r);
                return $r;
            });

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
            'filtros'   => $filtros
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('relatorio_registro_veiculos.pdf');
    }

    /**
     * Aplica filtros de formulário à query.
     */
    protected function aplicarFiltros($query, Request $request): void
    {
        // Placa (usa snapshot do registro)
        if ($request->filled('placa')) {
            $query->where('placa', 'like', '%' . $request->placa . '%');
        }

        // Tipo (OFICIAL / PARTICULAR / MOTO)
        if ($request->filled('tipo') && $request->tipo !== 'Todos') {
            $query->where('tipo', $request->tipo);
        }

        // Data inicial + hora
        if ($request->filled('data_inicial')) {
            try {
                $data = $request->data_inicial;
                $hora = $request->hora_inicial ?: '00:00';
                $inicio = Carbon::createFromFormat('Y-m-d H:i', "$data $hora");
                $query->where('horario_entrada', '>=', $inicio);
            } catch (\Exception $e) {
                Log::error('Erro ao processar data_inicial: ' . $e->getMessage());
            }
        }

        // Data final + hora
        if ($request->filled('data_final')) {
            try {
                $data = $request->data_final;
                $hora = $request->hora_final ?: '23:59';
                $fim = Carbon::createFromFormat('Y-m-d H:i', "$data $hora");
                $query->where('horario_saida', '<=', $fim);
            } catch (\Exception $e) {
                Log::error('Erro ao processar data_final: ' . $e->getMessage());
            }
        }

        // Localização (via relacionamento)
        if ($request->filled('localizacao') && $request->localizacao !== 'Todas') {
            $query->whereHas('estacionamento', function ($q) use ($request) {
                $q->where('localizacao', $request->localizacao);
            });
        }

        // Motorista Entrada (nome exato)
        if ($request->filled('nome_motorista_entrada')) {
            $nome = $request->nome_motorista_entrada;
            $query->whereHas('motoristaEntrada', function ($q) use ($nome) {
                $q->where('nome', $nome);
            });
        }

        if ($request->filled('nome_motorista_saida')) {
            $nome = $request->nome_motorista_saida;
            $query->where(function ($q) use ($nome) {
                // Caso oficial
                $q->whereHas('motoristaSaida', function ($q2) use ($nome) {
                    $q2->where('nome', $nome);
                })
                // Caso part/moto usando entrada
                ->orWhere(function ($q2) use ($nome) {
                    $q2->whereIn('tipo', ['PARTICULAR', 'MOTO'])
                       ->whereHas('motoristaEntrada', function ($q3) use ($nome) {
                           $q3->where('nome', $nome);
                       });
                })
                // Motorista_saida_outros (AcessoLiberado)
                ->orWhereHas('motoristaSaidaOutros', function ($q2) use ($nome) {
                    $q2->where('nome', $nome);
                });
            });
        }

        // Usuário Entrada
        if ($request->filled('usuario_entrada') && $request->usuario_entrada !== 'Todos') {
            $query->whereHas('usuarioEntrada', function ($q) use ($request) {
                $q->where('nome', $request->usuario_entrada);
            });
        }

        // Usuário Saída
        if ($request->filled('usuario_saida') && $request->usuario_saida !== 'Todos') {
            $query->whereHas('usuarioSaida', function ($q) use ($request) {
                $q->where('nome', $request->usuario_saida);
            });
        }
    }

    protected function resolverMotoristaSaida(RegistroVeiculo $r): string
    {
        // Se mapeado motoristaSaidaOutros (AcessoLiberado) 
        if ($r->relationLoaded('motoristaSaidaOutros') && $r->motoristaSaidaOutros) {
            return $r->motoristaSaidaOutros->nome;
        }

        if ($r->tipo === 'OFICIAL') {
            return optional($r->motoristaSaida)->nome
                ?: '-';
        }

        // PART / MOTO 
        return optional($r->motoristaEntrada)->nome
            ?: '-';
    }
}
