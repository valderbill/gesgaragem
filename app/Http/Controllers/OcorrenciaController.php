<?php

namespace App\Http\Controllers;

use App\Models\Ocorrencia;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OcorrenciaController extends Controller
{
    public function index()
    {
        // Carrega as ocorrências com o usuário relacionado
        $ocorrencias = Ocorrencia::with('usuario')->latest('horario')->paginate(10);
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
            'horario'     => Carbon::now(), // preenche automaticamente
            'usuario_id'  => auth()->id(),  // pega o usuário logado
        ]);

        return redirect()->route('ocorrencias.index')->with('success', 'Ocorrência registrada com sucesso.');
    }

    public function show($id)
    {
        $ocorrencia = Ocorrencia::with('usuario')->findOrFail($id);
        return view('ocorrencias.show', compact('ocorrencia'));
    }
}
