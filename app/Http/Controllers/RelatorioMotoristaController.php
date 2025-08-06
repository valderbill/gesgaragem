<?php

namespace App\Http\Controllers;

use App\Models\Motorista;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class RelatorioMotoristaController extends Controller
{
    public function index(Request $request)
    {
        // Iniciando a query para motoristas
        $query = Motorista::query();

        // Aplicando filtros
        if ($request->filled('nome')) {
            $query->where('nome', 'ILIKE', '%' . $request->nome . '%');
        }

        if ($request->filled('matricula')) {
            $query->where('matricula', 'ILIKE', '%' . $request->matricula . '%');
        }

        if (!is_null($request->ativo)) {
            $query->where('ativo', $request->ativo);
        }

        if ($request->filled('data_inicial')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->data_inicial));
        }

        if ($request->filled('data_final')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->data_final));
        }

        // Paginação dos motoristas
        $motoristas = $query->paginate(10);

        // Atualizando o caminho da view
        return view('relatorios.motoristas-oficiais.index', compact('motoristas'));
    }

    public function exportar(Request $request)
    {
        // Iniciando a query para exportação
        $query = Motorista::query();

        // Aplicando filtros para exportação
        if ($request->filled('nome')) {
            $query->where('nome', 'ILIKE', '%' . $request->nome . '%');
        }

        if ($request->filled('matricula')) {
            $query->where('matricula', 'ILIKE', '%' . $request->matricula . '%');
        }

        if (!is_null($request->ativo)) {
            $query->where('ativo', $request->ativo);
        }

        if ($request->filled('data_inicial')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->data_inicial));
        }

        if ($request->filled('data_final')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->data_final));
        }

        // Ordenação no PDF
        $motoristas = $query->orderBy('nome')->get();
        $filtros = $request->all();

        // Gerando o PDF
        $pdf = Pdf::loadView('relatorios.motoristas-oficiais.pdf', compact('motoristas', 'filtros'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('relatorio_motoristas.pdf');
    }

    public function exportarSelecionados(Request $request)
    {
        // Lógica de exportação de motoristas selecionados
        $motoristas = Motorista::whereIn('id', $request->motoristas_ids)->get();
        $filtros = $request->all();

        $pdf = Pdf::loadView('motoristas-oficiais.pdf', compact('motoristas', 'filtros'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('relatorio_motoristas_selecionados.pdf');
    }
}
