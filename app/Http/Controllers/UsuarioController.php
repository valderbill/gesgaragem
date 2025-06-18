<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Perfil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::with('perfil')->get();
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $perfis = Perfil::all();
        return view('usuarios.create', compact('perfis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'matricula' => 'required|string|max:100|unique:usuarios,matricula',
            'senha' => 'required|string|min:6',
            'perfil_id' => 'required|exists:perfis,id',
            'ativo' => 'required|boolean',
        ]);

        Usuario::create([
            'nome' => $request->nome,
            'matricula' => $request->matricula,
            'senha' => Hash::make($request->senha),
            'perfil_id' => $request->perfil_id,
            'ativo' => $request->ativo,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuário criado com sucesso.');
    }

    public function show(Usuario $usuario)
    {
        return view('usuarios.show', compact('usuario'));
    }

    public function edit(Usuario $usuario)
    {
        $perfis = Perfil::all();
        return view('usuarios.edit', compact('usuario', 'perfis'));
    }

    public function update(Request $request, Usuario $usuario)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'matricula' => 'required|string|max:100|unique:usuarios,matricula,' . $usuario->id,
            'perfil_id' => 'required|exists:perfis,id',
            'ativo' => 'required|boolean',
        ]);

        $usuario->nome = $request->nome;
        $usuario->matricula = $request->matricula;
        $usuario->perfil_id = $request->perfil_id;
        $usuario->ativo = $request->ativo;

        if ($request->filled('senha')) {
            $request->validate(['senha' => 'string|min:6']);
            $usuario->senha = Hash::make($request->senha);
        }

        $usuario->save();

        return redirect()->route('usuarios.index')->with('success', 'Usuário atualizado com sucesso.');
    }

    /**
     * Altera o status (ativo/inativo) do usuário.
     * Rota: PATCH usuarios/{usuario}/alternar-status
     */
    public function alternarStatus(Request $request, Usuario $usuario)
    {
        $request->validate(['ativo' => 'required|boolean']);

        $usuario->ativo = $request->ativo;
        $usuario->save();

        $status = $usuario->ativo ? 'ativado' : 'inativado';

        return redirect()->route('usuarios.index')->with('success', "Usuário {$status} com sucesso.");
    }

    /**
     * Redefine a senha do usuário para sua matrícula.
     * Rota: POST usuarios/{usuario}/reset-senha
     */
    public function resetSenha($id)
    {
        $usuario = Usuario::findOrFail($id);
        $novaSenha = $usuario->matricula;

        $usuario->senha = Hash::make($novaSenha);
        $usuario->save();

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Senha redefinida com sucesso para o usuário: ' . $usuario->nome)
            ->with('novaSenha', $novaSenha)
            ->with('usuarioId', $usuario->id);
    }
}
