<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Perfil;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RelatorioUsuarioController extends Controller
{
    /**
     * Exibe o relatório com filtros e paginação
     */
    public function index(Request $request)
    {
        // Monta query base com relacionamentos
        $query = Usuario::with(['perfil', 'criador', 'ativadoPor', 'inativadoPor']);

        // Filtros
        if ($request->filled('nome')) {
            $query->where('nome', 'ILIKE', '%' . $request->nome . '%');
        }

        if ($request->filled('matricula')) {
            $query->where('matricula', 'ILIKE', '%' . $request->matricula . '%');
        }

        if ($request->filled('ativo')) {
            $query->where('ativo', $request->ativo);
        }

        if ($request->filled('perfil_id')) {
            $query->where('perfil_id', $request->perfil_id);
        }

        if ($request->filled('data_inicial')) {
            $query->whereDate('created_at', '>=', $request->data_inicial);
        }

        if ($request->filled('data_final')) {
            $query->whereDate('created_at', '<=', $request->data_final);
        }

        // Paginação com 10 por página
        $usuarios = $query->paginate(10);

        // Perfis disponíveis para o filtro
        $perfis = Perfil::orderBy('nome')->get();

        // Envia para a view
        return view('relatorios.usuarios.index', compact('usuarios', 'perfis'));
    }

    /**
     * Gera PDF com os filtros aplicados
     */
    public function exportar(Request $request)
    {
        // Monta query base com relacionamentos
        $query = Usuario::with(['perfil', 'criador', 'ativadoPor', 'inativadoPor']);

        // Filtros (mesmo do index)
        if ($request->filled('nome')) {
            $query->where('nome', 'ILIKE', '%' . $request->nome . '%');
        }

        if ($request->filled('matricula')) {
            $query->where('matricula', 'ILIKE', '%' . $request->matricula . '%');
        }

        if ($request->filled('ativo')) {
            $query->where('ativo', $request->ativo);
        }

        if ($request->filled('perfil_id')) {
            $query->where('perfil_id', $request->perfil_id);
        }

        if ($request->filled('data_inicial')) {
            $query->whereDate('created_at', '>=', $request->data_inicial);
        }

        if ($request->filled('data_final')) {
            $query->whereDate('created_at', '<=', $request->data_final);
        }

        // Sem paginação, tudo no PDF
        $usuarios = $query->get();

        // Filtros para exibir no cabeçalho do PDF se necessário
        $filtros = $request->all();

        // Gera e retorna o PDF
        $pdf = Pdf::loadView('relatorios.usuarios.pdf', compact('usuarios', 'filtros'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('relatorio_usuarios.pdf');
    }
}
