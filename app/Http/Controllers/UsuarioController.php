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
            'password' => 'required|string|min:6',
            'perfil_id' => 'required|exists:perfis,id',
            'ativo' => 'required|boolean',
        ]);

        Usuario::create([
            'nome' => $request->nome,
            'matricula' => $request->matricula,
            'password' => $request->password, // Aqui só passe o password, o mutator do model já faz o hash
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

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:6']);
            $usuario->password = $request->password; // mutator do model vai aplicar bcrypt
        }

        $usuario->save();

        return redirect()->route('usuarios.index')->with('success', 'Usuário atualizado com sucesso.');
    }

    public function alternarStatus(Request $request, Usuario $usuario)
    {
        $request->validate(['ativo' => 'required|boolean']);

        $usuario->ativo = $request->ativo;
        $usuario->save();

        $status = $usuario->ativo ? 'ativado' : 'inativado';

        return redirect()->route('usuarios.index')->with('success', "Usuário {$status} com sucesso.");
    }

    public function resetSenha($id)
    {
        $usuario = Usuario::findOrFail($id);
        $novaSenha = $usuario->matricula;

        $usuario->password = $novaSenha; // model aplica bcrypt
        $usuario->save();

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Senha redefinida com sucesso para o usuário: ' . $usuario->nome)
            ->with('novaSenha', $novaSenha)
            ->with('usuarioId', $usuario->id);
    }
}
