<?php

namespace App\Http\Controllers;

use App\Models\Permissao;
use Illuminate\Http\Request;

class PermissaoController extends Controller
{
    public function index()
    {
        $permissoes = Permissao::paginate(10);
        return view('permissoes.index', compact('permissoes'));
    }

    public function create()
    {
        return view('permissoes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|unique:permissoes,nome',
            'descricao' => 'nullable|string',
        ]);

        Permissao::create($request->only(['nome', 'descricao']));

        return redirect()->route('permissoes.index')->with('success', 'Permissão criada com sucesso!');
    }

    public function show(Permissao $permissao)
    {
        return view('permissoes.show', compact('permissao'));
    }

    public function edit(Permissao $permissao)
    {
        return view('permissoes.edit', compact('permissao'));
    }

    public function update(Request $request, Permissao $permissao)
    {
        $request->validate([
            'nome' => 'required|unique:permissoes,nome,' . $permissao->id,
            'descricao' => 'nullable|string',
        ]);

        $permissao->update($request->only(['nome', 'descricao']));

        return redirect()->route('permissoes.index')->with('success', 'Permissão atualizada com sucesso!');
    }

    public function destroy(Permissao $permissao)
    {
        $permissao->delete();
        return redirect()->route('permissoes.index')->with('success', 'Permissão excluída com sucesso!');
    }
}
