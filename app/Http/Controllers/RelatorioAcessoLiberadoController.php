<?php

namespace App\Http\Controllers;

use App\Models\AcessoLiberado;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;  // <-- Importação do PDF

class RelatorioAcessoLiberadoController extends Controller
{
    public function index(Request $request)
    {
        // Inicia a consulta no modelo AcessoLiberado
        $acessos = AcessoLiberado::query();

        // Filtra por nome se fornecido
        if ($request->filled('nome')) {
            $acessos->where('nome', 'like', '%' . $request->nome . '%');
        }

        // Filtra por matrícula se fornecido
        if ($request->filled('matricula')) {
            $acessos->where('matricula', 'like', '%' . $request->matricula . '%');
        }

        // Filtra por status se fornecido
        if ($request->filled('status')) {
            $acessos->where('status', $request->status);
        }

        // Paginação de 10 itens por vez
        $acessos = $acessos->paginate(10);

        // Retorna a view com os acessos filtrados
        return view('relatorios.acessos_liberados.index', compact('acessos'));
    }

    public function exportar(Request $request)
    {
        // Inicia a consulta no modelo AcessoLiberado
        $acessos = AcessoLiberado::query();

        // Filtra por nome se fornecido
        if ($request->filled('nome')) {
            $acessos->where('nome', 'like', '%' . $request->nome . '%');
        }

        // Filtra por matrícula se fornecido
        if ($request->filled('matricula')) {
            $acessos->where('matricula', 'like', '%' . $request->matricula . '%');
        }

        // Filtra por status se fornecido
        if ($request->filled('status')) {
            $acessos->where('status', $request->status);
        }

        // Carregar o relacionamento 'criador' para acessar o nome do criador
        $acessos = $acessos->with('criador')->get();  // Carrega o relacionamento 'criador'

        // Obtém todos os filtros fornecidos
        $filtros = $request->all();

        // Gerar o PDF
        $pdf = Pdf::loadView('relatorios.acessos_liberados.pdf', compact('acessos', 'filtros'));

        // Retorna o PDF para download
        return $pdf->download('relatorio_acessos_liberados.pdf');
    }
}
