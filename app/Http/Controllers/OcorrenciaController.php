<?php

namespace App\Http\Controllers;

use App\Models\Ocorrencia;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OcorrenciaController extends Controller
{
    public function index(Request $request)
    {
        $usuario = Auth::user();
        $perfil = optional($usuario->perfil)->nome;

        // Inicia a query para buscar as ocorrências
        $query = Ocorrencia::with('usuario');

        // Filtros
        if ($perfil !== 'administrador') {
            // Usuário não administrador vê apenas suas próprias ocorrências
            $query->where('usuario_id', $usuario->id);
        }

        // Filtro por texto da ocorrência
        if ($request->filled('texto')) {
            // Alterado de 'ocorrencia' para 'descricao'
            $query->where('descricao', 'ILIKE', '%' . $request->texto . '%');
        }

        // Filtro por usuário
        if ($request->filled('usuario')) {
            $query->whereHas('usuario', function ($query) use ($request) {
                $query->where('nome', 'ILIKE', '%' . $request->usuario . '%');
            });
        }

        // Filtro por presença de acompanhamento
        if ($request->filled('possui_acompanhamento')) {
            $query->whereHas('acompanhamentos', function ($query) use ($request) {
                if ($request->possui_acompanhamento === 'sim') {
                    $query->whereNotNull('descricao');
                } else {
                    $query->whereNull('descricao');
                }
            });
        }

        // Filtro por data inicial
        if ($request->filled('data_inicial')) {
            $query->whereDate('horario', '>=', $request->data_inicial);
        }

        // Filtro por data final
        if ($request->filled('data_final')) {
            $query->whereDate('horario', '<=', $request->data_final);
        }

        // Paginação
        $ocorrencias = $query->latest('horario')->paginate(10);

        // Para o filtro de usuários
        $usuarios = Usuario::select('nome')->orderBy('nome')->get();

        return view('ocorrencias.index', compact('ocorrencias', 'usuarios'));
    }

    public function create()
    {
        return view('ocorrencias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'descricao' => 'required|string|max:1000',  // Agora esperamos 'descricao'
        ]);

        Ocorrencia::create([
            'descricao'  => $request->input('descricao'),  // Salvando 'descricao'
            'horario'    => now(),
            'usuario_id' => Auth::id(),
        ]);

        return redirect()->route('ocorrencias.index')->with('success', 'Ocorrência registrada com sucesso.');
    }

    public function show($id)
    {
        $ocorrencia = Ocorrencia::with(['usuario', 'acompanhamentos'])->findOrFail($id);
        return view('ocorrencias.show', compact('ocorrencia'));
    }

    public function edit($id)
    {
        $ocorrencia = Ocorrencia::with('usuario')->findOrFail($id);

        $usuario = Auth::user();
        $perfil = optional($usuario->perfil)->nome;

        if ($perfil !== 'administrador' && $ocorrencia->usuario_id !== $usuario->id) {
            return redirect()->route('ocorrencias.index')->with('error', 'Você não tem permissão para editar esta ocorrência.');
        }

        return view('ocorrencias.edit', compact('ocorrencia'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'descricao' => 'required|string|max:1000',  // Agora esperamos 'descricao'
        ]);

        $ocorrencia = Ocorrencia::findOrFail($id);

        $usuario = Auth::user();
        $perfil = optional($usuario->perfil)->nome;

        if ($perfil !== 'administrador' && $ocorrencia->usuario_id !== $usuario->id) {
            return redirect()->route('ocorrencias.index')->with('error', 'Você não tem permissão para atualizar esta ocorrência.');
        }

        $ocorrencia->update([
            'descricao' => $request->input('descricao'),  // Atualizando 'descricao'
        ]);

        return redirect()->route('ocorrencias.index')->with('success', 'Ocorrência atualizada com sucesso.');
    }

    public function destroy($id)
    {
        $ocorrencia = Ocorrencia::findOrFail($id);

        $usuario = Auth::user();
        $perfil = optional($usuario->perfil)->nome;

        if ($perfil !== 'administrador' && $ocorrencia->usuario_id !== $usuario->id) {
            return redirect()->route('ocorrencias.index')->with('error', 'Você não tem permissão para excluir esta ocorrência.');
        }

        $ocorrencia->delete();

        return redirect()->route('ocorrencias.index')->with('success', 'Ocorrência excluída com sucesso.');
    }
}
