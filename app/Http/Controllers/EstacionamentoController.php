<?php

namespace App\Http\Controllers;

use App\Models\Estacionamento;
use Illuminate\Http\Request;

class EstacionamentoController extends Controller
{
    /**
     * Lista de estacionamentos.
     */
    public function index()
    {
        $estacionamentos = Estacionamento::all();
        return view('estacionamentos.index', compact('estacionamentos'));
    }

    /**
     * Formulário de criação.
     */
    public function create()
    {
        return view('estacionamentos.create');
    }

    /**
     * Salva novo estacionamento.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'localizacao'        => 'required|string|max:100',
            'vagas_particulares' => 'nullable|integer|min:0',
            'vagas_oficiais'     => 'nullable|integer|min:0',
            'vagas_motos'        => 'nullable|integer|min:0',
        ]);

        // Evita null no banco (bom p/ contadores dos cards)
        $validated = $this->normalizarVagas($validated);

        Estacionamento::create($validated);

        return redirect()
            ->route('estacionamentos.index')
            ->with('success', 'Estacionamento criado com sucesso!');
    }

    /**
     * Exibe um estacionamento.
     */
    public function show(Estacionamento $estacionamento)
    {
        return view('estacionamentos.show', compact('estacionamento'));
    }

    /**
     * Formulário de edição.
     */
    public function edit(Estacionamento $estacionamento)
    {
        return view('estacionamentos.edit', compact('estacionamento'));
    }

    /**
     * Atualiza um estacionamento.
     */
    public function update(Request $request, Estacionamento $estacionamento)
    {
        $validated = $request->validate([
            'localizacao'        => 'required|string|max:100',
            'vagas_particulares' => 'nullable|integer|min:0',
            'vagas_oficiais'     => 'nullable|integer|min:0',
            'vagas_motos'        => 'nullable|integer|min:0',
        ]);

        $validated = $this->normalizarVagas($validated);

        $estacionamento->update($validated);

        return redirect()
            ->route('estacionamentos.index')
            ->with('success', 'Estacionamento atualizado com sucesso!');
    }

    /**
     * Exclui um estacionamento.
     */
    public function destroy(Estacionamento $estacionamento)
    {
        $estacionamento->delete();

        return redirect()
            ->route('estacionamentos.index')
            ->with('success', 'Estacionamento excluído com sucesso!');
    }

    /*
    |--------------------------------------------------------------------------
    | Seleção de Estacionamento para sessão
    |--------------------------------------------------------------------------
    */

    /**
     * Tela de seleção do estacionamento ativo.
     */
    public function selecionar()
    {
        $estacionamentos = Estacionamento::all();
        return view('estacionamentos.selecionar', compact('estacionamentos'));
    }

    /**
     * Define o estacionamento ativo (salva na sessão).
     */
    public function definir(Request $request)
    {
        $request->validate([
            'estacionamento_id' => 'required|exists:estacionamentos,id'
        ]);

        $estacionamento = Estacionamento::findOrFail($request->estacionamento_id);

        // Salva o ID na sessão (persiste até trocar)
        session(['estacionamento_id' => $estacionamento->id]);
        session()->save(); // força persistência imediata (útil em alguns drivers)

        return redirect()
            ->route('registro_veiculos.index')
            ->with('success', 'Estacionamento "' . $estacionamento->localizacao . '" selecionado com sucesso.');
    }

    /**
     * Normaliza campos de vagas para nunca ficarem null.
     */
    private function normalizarVagas(array $data): array
    {
        $data['vagas_particulares'] = $data['vagas_particulares'] ?? 0;
        $data['vagas_oficiais']     = $data['vagas_oficiais']     ?? 0;
        $data['vagas_motos']        = $data['vagas_motos']        ?? 0;
        return $data;
    }
}
