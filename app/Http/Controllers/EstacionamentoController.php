<?php

namespace App\Http\Controllers;

use App\Models\Estacionamento;
use Illuminate\Http\Request;

class EstacionamentoController extends Controller
{
    public function index()
    {
        $estacionamentos = Estacionamento::all();
        return view('estacionamentos.index', compact('estacionamentos'));
    }

    public function create()
    {
        return view('estacionamentos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'localizacao' => 'required|string|max:100',
            'vagas_particulares' => 'nullable|integer|min:0',
            'vagas_oficiais' => 'nullable|integer|min:0',
            'vagas_motos' => 'nullable|integer|min:0',
        ]);

        Estacionamento::create($validated);

        return redirect()->route('estacionamentos.index')->with('success', 'Estacionamento criado com sucesso!');
    }

    public function show(Estacionamento $estacionamento)
    {
        return view('estacionamentos.show', compact('estacionamento'));
    }

    public function edit(Estacionamento $estacionamento)
    {
        return view('estacionamentos.edit', compact('estacionamento'));
    }

    public function update(Request $request, Estacionamento $estacionamento)
    {
        $validated = $request->validate([
            'localizacao' => 'required|string|max:100',
            'vagas_particulares' => 'nullable|integer|min:0',
            'vagas_oficiais' => 'nullable|integer|min:0',
            'vagas_motos' => 'nullable|integer|min:0',
        ]);

        $estacionamento->update($validated);

        return redirect()->route('estacionamentos.index')->with('success', 'Estacionamento atualizado com sucesso!');
    }

    public function destroy(Estacionamento $estacionamento)
    {
        $estacionamento->delete();

        return redirect()->route('estacionamentos.index')->with('success', 'Estacionamento excluído com sucesso!');
    }

    // ✅ Adicionados para seleção de estacionamento
    public function selecionar()
    {
        $estacionamentos = Estacionamento::all();
        return view('estacionamentos.selecionar', compact('estacionamentos'));
    }

    public function definir(Request $request)
    {
        $request->validate([
            'estacionamento_id' => 'required|exists:estacionamentos,id'
        ]);

        session(['estacionamento_id' => $request->estacionamento_id]);

        return redirect()->route('registro_veiculos.index')->with('success', 'Estacionamento selecionado com sucesso.');
    }
}
