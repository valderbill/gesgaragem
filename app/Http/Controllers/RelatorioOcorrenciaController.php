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

        if ($request->filled('texto')) {
            $query->where('ocorrencia', 'ILIKE', '%' . $request->texto . '%');
        }

        if ($request->filled('usuario')) {
            $query->whereHas('usuario', fn($q) => $q->where('nome', $request->usuario));
        }

        if ($request->filled('possui_acompanhamento')) {
            if ($request->possui_acompanhamento === 'sim') {
                $query->has('acompanhamentos');
            } elseif ($request->possui_acompanhamento === 'nao') {
                $query->doesntHave('acompanhamentos');
            }
        }

        if ($request->filled('data_inicial')) {
            $query->whereDate('horario', '>=', $request->data_inicial);
        }

        if ($request->filled('data_final')) {
            $query->whereDate('horario', '<=', $request->data_final);
        }

        $ocorrencias = $query->orderBy('horario', 'desc')->paginate(20);
        $usuarios = Usuario::select('nome')->orderBy('nome')->get();

        return view('relatorios.ocorrencias.index', compact('ocorrencias', 'usuarios'));
    }

    public function exportar(Request $request)
    {
        $query = Ocorrencia::with(['usuario', 'acompanhamentos']);

        if ($request->filled('texto')) {
            $query->where('ocorrencia', 'ILIKE', '%' . $request->texto . '%');
        }

        if ($request->filled('usuario')) {
            $query->whereHas('usuario', fn($q) => $q->where('nome', $request->usuario));
        }

        if ($request->filled('possui_acompanhamento')) {
            if ($request->possui_acompanhamento === 'sim') {
                $query->has('acompanhamentos');
            } elseif ($request->possui_acompanhamento === 'nao') {
                $query->doesntHave('acompanhamentos');
            }
        }

        if ($request->filled('data_inicial')) {
            $query->whereDate('horario', '>=', $request->data_inicial);
        }

        if ($request->filled('data_final')) {
            $query->whereDate('horario', '<=', $request->data_final);
        }

        $ocorrencias = $query->orderBy('horario', 'desc')->get();
        $filtros = $request->except(['page']);

        return Pdf::loadView('relatorios.ocorrencias.pdf', compact('ocorrencias', 'filtros'))
            ->stream('relatorio_ocorrencias.pdf');
    }

    public function exportarIndividual($id)
    {
        $ocorrencia = Ocorrencia::with(['usuario', 'acompanhamentos.usuario'])->findOrFail($id);

        return Pdf::loadView('relatorios.ocorrencias.pdf_individual', compact('ocorrencia'))
            ->stream("ocorrencia_{$id}.pdf");
    }

    public function exportarSelecionadas(Request $request)
    {
        $ids = $request->input('selecionadas', []);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Nenhuma ocorrência selecionada.');
        }

        $ocorrencias = Ocorrencia::with(['usuario', 'acompanhamentos'])->whereIn('id', $ids)->get();
        $filtros = ['Selecionadas manualmente'];

        return Pdf::loadView('relatorios.ocorrencias.pdf', compact('ocorrencias', 'filtros'))
            ->download('ocorrencias_selecionadas.pdf');
    }
}
