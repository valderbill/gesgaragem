<?php

namespace App\Http\Controllers;

use App\Models\RelatorioVeiculo;
use App\Models\Veiculo;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth; // <- Importação adicionada

class RelatorioVeiculoController extends Controller
{
    public function index(Request $request)
    {
        $query = Veiculo::query();

        // Filtros por campos de texto (case-insensitive)
        if ($request->filled('placa')) {
            $query->where('placa', 'ILIKE', '%' . $request->placa . '%');
        }

        if ($request->filled('modelo')) {
            $query->where('modelo', 'ILIKE', '%' . $request->modelo . '%');
        }

        if ($request->filled('marca')) {
            $query->where('marca', 'ILIKE', '%' . $request->marca . '%');
        }

        if ($request->filled('cor')) {
            $query->where('cor', 'ILIKE', '%' . $request->cor . '%');
        }

        // Filtro por tipos (array)
        if ($request->filled('tipos') && is_array($request->tipos)) {
            $query->whereIn('tipo', $request->tipos);
        }

        // Filtro por intervalo de datas usando a coluna criada por você
        if ($request->filled('data_inicial')) {
            $query->whereDate('criado_em', '>=', $request->data_inicial);
        }

        if ($request->filled('data_final')) {
            $query->whereDate('criado_em', '<=', $request->data_final);
        }

        // Carregar relacionamentos importantes para exibir no relatório
        $veiculos = $query->with(['motoristaOficial', 'acesso', 'criador'])->paginate(20);

        return view('relatorios.veiculos.index', compact('veiculos'));
    }

    public function create()
    {
        return view('relatorios.veiculos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'tipos' => 'nullable|array',
        ]);

        $data = $request->all();

        $data['user_id'] = Auth::id(); // registra o usuário logado como criador
        $data['criado_por'] = Auth::id(); // se sua tabela tiver esse campo
        $data['criado_em'] = now(); // registra a data e hora atual

        RelatorioVeiculo::create($data);

        return redirect()->route('relatorios.veiculos.index')->with('success', 'Relatório salvo com sucesso.');
    }

    public function show(RelatorioVeiculo $relatorio)
    {
        $query = Veiculo::query();

        if (!empty($relatorio->tipos)) {
            $query->whereIn('tipo', $relatorio->tipos);
        }

        $veiculos = $query->with(['motoristaOficial', 'acesso', 'criador'])->paginate(20);

        return view('relatorios.veiculos.show', compact('relatorio', 'veiculos'));
    }

    public function edit(RelatorioVeiculo $relatorio)
    {
        return view('relatorios.veiculos.edit', compact('relatorio'));
    }

    public function update(Request $request, RelatorioVeiculo $relatorio)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'tipos' => 'nullable|array',
        ]);

        $relatorio->update([
            'nome' => $request->nome,
            'tipos' => $request->tipos,
        ]);

        return redirect()->route('relatorios.veiculos.index')->with('success', 'Relatório atualizado com sucesso.');
    }

    public function exportar(Request $request)
    {
        $query = Veiculo::query();

        if ($request->filled('placa')) {
            $query->where('placa', 'ILIKE', '%' . $request->placa . '%');
        }

        if ($request->filled('modelo')) {
            $query->where('modelo', 'ILIKE', '%' . $request->modelo . '%');
        }

        if ($request->filled('marca')) {
            $query->where('marca', 'ILIKE', '%' . $request->marca . '%');
        }

        if ($request->filled('cor')) {
            $query->where('cor', 'ILIKE', '%' . $request->cor . '%');
        }

        if ($request->filled('tipos') && is_array($request->tipos)) {
            $query->whereIn('tipo', $request->tipos);
        }

        if ($request->filled('data_inicial')) {
            $query->whereDate('criado_em', '>=', $request->data_inicial);
        }

        if ($request->filled('data_final')) {
            $query->whereDate('criado_em', '<=', $request->data_final);
        }

        $veiculos = $query->with(['motoristaOficial', 'acesso', 'criador'])->get();

        $pdf = Pdf::loadView('relatorios.veiculos.pdf', [
            'veiculos' => $veiculos,
            'filtros' => $request->all(),
        ]);

        return $pdf->download('relatorio_veiculos.pdf');
    }
}
