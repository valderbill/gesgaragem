<?php

namespace App\Http\Controllers;

use App\Models\Ocorrencia;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OcorrenciaController extends Controller
{
    public function index()
    {
        $usuario = auth()->user();
        $perfil = optional($usuario->perfil)->nome;

        if (in_array($perfil, ['vigilante', 'recepcionista'])) {
            // Apenas ocorrências do próprio usuário
            $ocorrencias = Ocorrencia::with('usuario')
                ->where('usuario_id', $usuario->id)
                ->latest('horario')
                ->paginate(10);
        } else {
            // Administrador ou outros veem tudo
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
            'ocorrencia' => 'required|string',
        ]);

        Ocorrencia::create([
            'ocorrencia'  => $request->ocorrencia,
            'horario'     => now(), // pega horário atual
            'usuario_id'  => auth()->id(), // pega o usuário logado
        ]);

        return redirect()->route('ocorrencias.index')->with('success', 'Ocorrência registrada com sucesso.');
    }

    public function show($id)
    {
        $ocorrencia = Ocorrencia::with('usuario')->findOrFail($id);
        return view('ocorrencias.show', compact('ocorrencia'));
    }

    public function edit($id)
    {
        $ocorrencia = Ocorrencia::with('usuario')->findOrFail($id);
        return view('ocorrencias.edit', compact('ocorrencia'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'ocorrencia' => 'required|string',
        ]);

        $ocorrencia = Ocorrencia::findOrFail($id);

        $ocorrencia->update([
            'ocorrencia'  => $request->ocorrencia,
        ]);

        return redirect()->route('ocorrencias.index')->with('success', 'Ocorrência atualizada com sucesso.');
    }

    public function destroy($id)
    {
        $ocorrencia = Ocorrencia::findOrFail($id);
        $ocorrencia->delete();

        return redirect()->route('ocorrencias.index')->with('success', 'Ocorrência excluída com sucesso.');
    }
}
