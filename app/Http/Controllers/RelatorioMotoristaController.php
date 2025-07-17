<?php

namespace App\Http\Controllers;

use App\Models\Motorista;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RelatorioMotoristaController extends Controller
{
    public function index(Request $request)
    {
        $query = Motorista::query();

        if ($request->filled('nome')) {
            $query->where('nome', 'ILIKE', '%' . $request->nome . '%');
        }

        if ($request->filled('matricula')) {
            $query->where('matricula', 'ILIKE', '%' . $request->matricula . '%');
        }

        if ($request->has('ativo') && $request->ativo !== '') {
            $query->where('ativo', $request->ativo);
        }

        $motoristas = $query->orderBy('nome')->paginate(20);

        return view('relatorios.motoristas-oficiais.index', compact('motoristas'));
    }

    public function exportar(Request $request)
    {
        $query = Motorista::query();

        if ($request->filled('nome')) {
            $query->where('nome', 'ILIKE', '%' . $request->nome . '%');
        }

        if ($request->filled('matricula')) {
            $query->where('matricula', 'ILIKE', '%' . $request->matricula . '%');
        }

        if ($request->has('ativo') && $request->ativo !== '') {
            $query->where('ativo', $request->ativo);
        }

        $motoristas = $query->orderBy('nome')->get();
        $filtros = $request->except(['page']);

        return Pdf::loadView('relatorios.motoristas-oficiais.pdf', compact('motoristas', 'filtros'))
            ->stream('relatorio_motoristas_oficiais.pdf');
    }
}
