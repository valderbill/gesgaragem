<?php

namespace App\Http\Controllers;

use App\Models\AcessoLiberado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $dados = $request->all();
        $dados['usuario_id'] = Auth::id();
        $dados['status'] = $request->has('status') ? $request->status : true; // padrão: ativo

        AcessoLiberado::create($dados);

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

        $dados = $request->all();
        $dados['status'] = $request->has('status') ? $request->status : true;

        $acesso = AcessoLiberado::findOrFail($id);
        $acesso->update($dados);

        return redirect()->route('acessos_liberados.index')->with('success', 'Acesso liberado atualizado com sucesso!');
    }

    // Atualizar status (ativo/inativo)
    public function alterarStatus($id)
    {
        $acesso = AcessoLiberado::findOrFail($id);
        $acesso->status = !$acesso->status;
        $acesso->save();

        return redirect()->route('acessos_liberados.index')->with('success', 'Status alterado com sucesso!');
    }

    // Buscar por nome ou matrícula
    public function buscar(Request $request)
    {
        $query = $request->input('q');

        $resultados = AcessoLiberado::where('nome', 'like', "%$query%")
            ->orWhere('matricula', 'like', "%$query%")
            ->limit(10)
            ->get();

        return response()->json($resultados);
    }
}
