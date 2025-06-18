<?php

namespace App\Http\Controllers;

use App\Models\Ocorrencia;
use App\Models\Veiculo;
use App\Models\Usuario;
use Illuminate\Http\Request;

class OcorrenciaController extends Controller
{
    public function index()
    {
        $ocorrencias = Ocorrencia::with(['veiculo', 'usuario'])->paginate(10);
        return view('ocorrencias.index', compact('ocorrencias'));
    }

    public function create()
    {
        $veiculos = Veiculo::all();
        $usuarios = Usuario::all();

        return view('ocorrencias.create', compact('veiculos', 'usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'placa' => 'required|exists:veiculos,placa',
            'ocorrencia' => 'required|string',
            'horario' => 'required|date',
            'usuario_id' => 'required|exists:usuarios,id',
        ]);

        Ocorrencia::create($request->all());

        return redirect()->route('ocorrencias.index')->with('success', 'Ocorrência criada com sucesso.');
    }

    public function show($id)
    {
        $ocorrencia = Ocorrencia::with(['veiculo', 'usuario'])->findOrFail($id);
        return view('ocorrencias.show', compact('ocorrencia'));
    }

    public function edit($id)
    {
        $ocorrencia = Ocorrencia::findOrFail($id);
        $veiculos = Veiculo::all();
        $usuarios = Usuario::all();

        return view('ocorrencias.edit', compact('ocorrencia', 'veiculos', 'usuarios'));
    }

    public function update(Request $request, $id)
    {
        $ocorrencia = Ocorrencia::findOrFail($id);

        $request->validate([
            'placa' => 'required|exists:veiculos,placa',
            'ocorrencia' => 'required|string',
            'horario' => 'required|date',
            'usuario_id' => 'required|exists:usuarios,id',
        ]);

        $ocorrencia->update($request->all());

        return redirect()->route('ocorrencias.index')->with('success', 'Ocorrência atualizada com sucesso.');
    }

    public function destroy($id)
    {
        $ocorrencia = Ocorrencia::findOrFail($id);
        $ocorrencia->delete();

        return redirect()->route('ocorrencias.index')->with('success', 'Ocorrência deletada com sucesso.');
    }
}
