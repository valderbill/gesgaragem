<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Perfil;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class RelatorioUsuarioController extends Controller
{
    public function index(Request $request)
    {
        $query = Usuario::with(['perfil']);

        if ($request->filled('nome')) {
            $query->where('nome', 'ILIKE', '%' . $request->nome . '%');
        }

        if ($request->filled('matricula')) {
            $query->where('matricula', 'ILIKE', '%' . $request->matricula . '%');
        }

        if (!is_null($request->ativo)) {
            $query->where('ativo', $request->ativo);
        }

        if ($request->filled('perfil_id')) {
            $query->where('perfil_id', $request->perfil_id);
        }

        if ($request->filled('data_inicial')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->data_inicial));
        }

        if ($request->filled('data_final')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->data_final));
        }

        $usuarios = $query->paginate(10);
        $perfis = Perfil::orderBy('nome')->get();

        return view('relatorios.usuarios.index', compact('usuarios', 'perfis'));
    }

    public function exportar(Request $request)
    {
        $query = Usuario::with(['perfil']);

        if ($request->filled('nome')) {
            $query->where('nome', 'ILIKE', '%' . $request->nome . '%');
        }

        if ($request->filled('matricula')) {
            $query->where('matricula', 'ILIKE', '%' . $request->matricula . '%');
        }

        if (!is_null($request->ativo)) {
            $query->where('ativo', $request->ativo);
        }

        if ($request->filled('perfil_id')) {
            $query->where('perfil_id', $request->perfil_id);
        }

        if ($request->filled('data_inicial')) {
            $query->whereDate('created_at', '>=', Carbon::parse($request->data_inicial));
        }

        if ($request->filled('data_final')) {
            $query->whereDate('created_at', '<=', Carbon::parse($request->data_final));
        }

        $usuarios = $query->orderBy('nome')->get(); // ordenação no PDF
        $filtros = $request->all();

        $pdf = Pdf::loadView('relatorios.usuarios.pdf', compact('usuarios', 'filtros'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('relatorio_usuarios.pdf');
    }
}
