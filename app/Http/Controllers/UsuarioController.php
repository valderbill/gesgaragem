<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Perfil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    // Lista usuários para o administrador
    public function index()
    {
        $usuarios = Usuario::with('perfil')->get(); // TODOS os usuários, sem filtro
        return view('usuarios.index', compact('usuarios'));
    }

    // Exibe o formulário de criação de usuário
    public function create()
    {
        $perfis = Perfil::all();
        return view('usuarios.create', compact('perfis'));
    }

    // Armazena um novo usuário
    public function store(Request $request)
    {
        // Validação dos campos
        $request->validate([
            'nome' => 'required|string|max:255',
            'matricula' => 'required|string|max:100|unique:usuarios,matricula',
            'senha' => 'required|string|min:8|confirmed',  // Confirmação de senha
            'perfil_id' => 'required|exists:perfis,id',
            'ativo' => 'required|boolean',
        ]);

        // Criação do usuário com os dados validados
        Usuario::create([
            'nome' => $request->nome,
            'matricula' => $request->matricula,
            'senha' => $request->senha,  // Já é criptografado pelo mutator no model
            'perfil_id' => $request->perfil_id,
            'ativo' => $request->ativo,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuário criado com sucesso.');
    }

    // Exibe os detalhes do usuário
    public function show(Usuario $usuario)
    {
        return view('usuarios.show', compact('usuario'));
    }

    // Exibe o formulário de edição do usuário
    public function edit(Usuario $usuario)
    {
        $perfis = Perfil::all();
        return view('usuarios.edit', compact('usuario', 'perfis'));
    }

    // Atualiza os dados de um usuário
    public function update(Request $request, Usuario $usuario)
    {
        // Validação dos campos (não incluindo a senha diretamente)
        $request->validate([
            'nome' => 'required|string|max:255',
            'matricula' => 'required|string|max:100|unique:usuarios,matricula,' . $usuario->id,
            'perfil_id' => 'required|exists:perfis,id',
            'ativo' => 'required|boolean',
        ]);

        // Atualiza os dados principais
        $usuario->nome = $request->nome;
        $usuario->matricula = $request->matricula;
        $usuario->perfil_id = $request->perfil_id;
        $usuario->ativo = $request->ativo;

        // Atualiza a senha, se fornecida
        if ($request->filled('senha')) {
            $request->validate(['senha' => 'string|min:8|confirmed']);
            $usuario->senha = $request->senha;  // Já criptografado pelo mutator do model
        }

        $usuario->save();

        return redirect()->route('usuarios.index')->with('success', 'Usuário atualizado com sucesso.');
    }

    // Alterna o status ativo/inativo do usuário
    public function toggleStatus(Request $request, $id)
    {
        $request->validate(['ativo' => 'required|boolean']);

        $usuario = Usuario::findOrFail($id);
        $usuario->ativo = $request->input('ativo');
        $usuario->save();

        return redirect()->route('usuarios.index')->with('success', 'Status do usuário atualizado com sucesso.');
    }

    // Reseta a senha do usuário para a matrícula
    public function resetSenha($id)
    {
        $usuario = Usuario::findOrFail($id);
        $novaSenha = $usuario->matricula;

        $usuario->senha = $novaSenha;  // Mutator cuida da criptografia
        $usuario->save();

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Senha redefinida com sucesso para o usuário: ' . $usuario->nome)
            ->with('novaSenha', $novaSenha)
            ->with('usuarioId', $usuario->id);
    }

    // ---------------------------------
    // Métodos para autenticação
    // ---------------------------------

    // Exibe o formulário de login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Processa o login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'matricula' => ['required', 'string'],
            'senha' => ['required', 'string'],
        ]);

        // Usando Auth::attempt com campo senha customizado, que o model já define
        if (Auth::attempt(['matricula' => $credentials['matricula'], 'senha' => $credentials['senha'], 'ativo' => true])) {
            $request->session()->regenerate();

            return redirect()->intended('/dashboard'); // Rota após login
        }

        return back()->withErrors([
            'matricula' => 'Matrícula ou senha incorretos, ou usuário inativo.',
        ])->onlyInput('matricula');
    }

    // Faz logout do usuário
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
