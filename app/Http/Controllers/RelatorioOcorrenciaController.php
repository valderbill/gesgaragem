<?php

namespace App\Http\Controllers;

use App\Models\Ocorrencia;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RelatorioOcorrenciaController extends Controller
{
    public function index(Request $request)
    {
        $query = Ocorrencia::with(['usuario', 'acompanhamentos']);

        // Filtro de texto corrigido para a coluna 'descricao'
        if ($request->filled('texto')) {
            $query->where('descricao', 'ILIKE', '%' . $request->texto . '%');
        }

        // Filtro de usuário
        if ($request->filled('usuario')) {
            $query->whereHas('usuario', fn($q) => $q->where('nome', $request->usuario));
        }

        // Filtro de acompanhamentos
        if ($request->filled('possui_acompanhamento')) {
            if ($request->possui_acompanhamento === 'sim') {
                $query->has('acompanhamentos');
            } elseif ($request->possui_acompanhamento === 'nao') {
                $query->doesntHave('acompanhamentos');
            }
        }

        // Filtro de data inicial
        if ($request->filled('data_inicial')) {
            $query->whereDate('horario', '>=', $request->data_inicial);
        }

        // Filtro de data final
        if ($request->filled('data_final')) {
            $query->whereDate('horario', '<=', $request->data_final);
        }

        // Obter as ocorrências com a ordenação desejada
        $ocorrencias = $query->orderBy('horario', 'desc')->paginate(20);

        // Carregar os usuários para o filtro de usuário
        $usuarios = Usuario::select('nome')->orderBy('nome')->get();

        // Retornar a view com as ocorrências e usuários
        return view('relatorios.ocorrencias.index', compact('ocorrencias', 'usuarios'));
    }

    public function exportar(Request $request)
    {
        $query = Ocorrencia::with(['usuario', 'acompanhamentos']);

        // Filtro de texto corrigido para a coluna 'descricao'
        if ($request->filled('texto')) {
            $query->where('descricao', 'ILIKE', '%' . $request->texto . '%');
        }

        // Filtro de usuário
        if ($request->filled('usuario')) {
            $query->whereHas('usuario', fn($q) => $q->where('nome', $request->usuario));
        }

        // Filtro de acompanhamentos
        if ($request->filled('possui_acompanhamento')) {
            if ($request->possui_acompanhamento === 'sim') {
                $query->has('acompanhamentos');
            } elseif ($request->possui_acompanhamento === 'nao') {
                $query->doesntHave('acompanhamentos');
            }
        }

        // Filtro de data inicial
        if ($request->filled('data_inicial')) {
            $query->whereDate('horario', '>=', $request->data_inicial);
        }

        // Filtro de data final
        if ($request->filled('data_final')) {
            $query->whereDate('horario', '<=', $request->data_final);
        }

        // Obter as ocorrências com a ordenação desejada
        $ocorrencias = $query->orderBy('horario', 'desc')->get();

        // Excluir o parâmetro de página dos filtros para exportação
        $filtros = $request->except(['page']);

        // Gerar o PDF com os dados
        return Pdf::loadView('relatorios.ocorrencias.pdf', compact('ocorrencias', 'filtros'))
            ->stream('relatorio_ocorrencias.pdf');
    }

    public function exportarIndividual($id)
    {
        // Carregar a ocorrência com os acompanhamentos e o usuário
        $ocorrencia = Ocorrencia::with(['usuario', 'acompanhamentos.usuario'])->findOrFail($id);

        // Gerar o PDF para a ocorrência individual
        return Pdf::loadView('relatorios.ocorrencias.pdf_individual', compact('ocorrencia'))
            ->stream("ocorrencia_{$id}.pdf");
    }

    public function exportarSelecionadas(Request $request)
    {
        // Obter os IDs das ocorrências selecionadas
        $ids = $request->input('selecionadas', []);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Nenhuma ocorrência selecionada.');
        }

        // Obter as ocorrências selecionadas
        $ocorrencias = Ocorrencia::with(['usuario', 'acompanhamentos'])->whereIn('id', $ids)->get();

        // Definir um filtro indicando que as ocorrências foram selecionadas manualmente
        $filtros = ['Selecionadas manualmente'];

        // Gerar o PDF para as ocorrências selecionadas
        return Pdf::loadView('relatorios.ocorrencias.pdf', compact('ocorrencias', 'filtros'))
            ->download('ocorrencias_selecionadas.pdf');
    }
}
