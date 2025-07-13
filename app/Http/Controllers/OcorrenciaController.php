<?php

namespace App\Http\Controllers;

use App\Models\Ocorrencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OcorrenciaController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();
        $perfil = optional($usuario->perfil)->nome;

        if ($perfil !== 'administrador') {
            // Mostra somente ocorrências do próprio usuário
            $ocorrencias = Ocorrencia::with('usuario')
                ->where('usuario_id', $usuario->id)
                ->latest('horario')
                ->paginate(10);
        } else {
            // Administrador vê todas
            $ocorrencias = Ocorrencia::with('usuario')
                ->latest('horario')
                ->paginate(10);
        }

        return view('ocorrencias.index', compact('ocorrencias'));
    }

    public function create()
    {
        return view('ocorrencias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ocorrencia' => 'required|string|max:1000',
        ]);

        Ocorrencia::create([
            'ocorrencia'  => $request->input('ocorrencia'),
            'horario'     => now(),
            'usuario_id'  => Auth::id(),
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
            'ocorrencia' => 'required|string|max:1000',
        ]);

        $ocorrencia = Ocorrencia::findOrFail($id);

        $usuario = Auth::user();
        $perfil = optional($usuario->perfil)->nome;

        if ($perfil !== 'administrador' && $ocorrencia->usuario_id !== $usuario->id) {
            return redirect()->route('ocorrencias.index')->with('error', 'Você não tem permissão para atualizar esta ocorrência.');
        }

        $ocorrencia->update([
            'ocorrencia' => $request->input('ocorrencia'),
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
