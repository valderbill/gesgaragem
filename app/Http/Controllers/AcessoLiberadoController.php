<?php

namespace App\Http\Controllers;

use App\Models\AcessoLiberado;
use Illuminate\Http\Request;

class AcessoLiberadoController extends Controller
{
    // Exibir lista paginada
    public function index()
    {
        $acessos = AcessoLiberado::orderBy('created_at', 'desc')->paginate(10);
        return view('acessos_liberados.index', compact('acessos'));
    }

    // Mostrar formulário para criar
    public function create()
    {
        return view('acessos_liberados.create');
    }

    // Salvar novo registro
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'matricula' => 'required|string|max:255',
           
        ]);

        AcessoLiberado::create($request->all());

        return redirect()->route('acessos_liberados.index')->with('success', 'Acesso liberado criado com sucesso!');
    }

    // Mostrar um registro específico
    public function show($id)
    {
        $acesso = AcessoLiberado::findOrFail($id);
        return view('acessos_liberados.show', compact('acesso'));
    }

    // Mostrar formulário para editar
    public function edit($id)
    {
        $acesso = AcessoLiberado::findOrFail($id);
        return view('acessos_liberados.edit', compact('acesso'));
    }

    // Atualizar registro existente
    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'matricula' => 'required|string|max:255',
           
        ]);

        $acesso = AcessoLiberado::findOrFail($id);
        $acesso->update($request->all());

        return redirect()->route('acessos_liberados.index')->with('success', 'Acesso liberado atualizado com sucesso!');
    }

    // Deletar registro
    public function destroy($id)
    {
        $acesso = AcessoLiberado::findOrFail($id);
        $acesso->delete();

        return redirect()->route('acessos_liberados.index')->with('success', 'Acesso liberado deletado com sucesso!');
    }
}
