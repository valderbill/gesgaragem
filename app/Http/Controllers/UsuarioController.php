<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Perfil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    // Lista todos os usuários
    public function index()
    {
        $usuarios = Usuario::with('perfil')->get();
        return view('usuarios.index', compact('usuarios'));
    }

    // Formulário de criação de usuário
    public function create()
    {
        $perfis = Perfil::all();
        return view('usuarios.create', compact('perfis'));
    }

    // Armazena novo usuário
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'matricula' => 'required|string|max:100|unique:usuarios,matricula',
            'senha' => 'required|string|min:8|confirmed',
            'perfil_id' => 'required|exists:perfis,id',
            'ativo' => 'required|boolean',
        ]);

        Usuario::create([
            'nome' => $request->nome,
            'matricula' => $request->matricula,
            'senha' => $request->senha, // Mutator aplica hash
            'perfil_id' => $request->perfil_id,
            'ativo' => $request->ativo,
            'criado_por' => Auth::id(), // registra quem criou, se estiver autenticado
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuário criado com sucesso.');
    }

    // Exibe detalhes de um usuário
    public function show(Usuario $usuario)
    {
        return view('usuarios.show', compact('usuario'));
    }

    // Formulário de edição
    public function edit(Usuario $usuario)
    {
        $perfis = Perfil::all();
        return view('usuarios.edit', compact('usuario', 'perfis'));
    }

    // Atualiza dados do usuário
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
            $request->validate([
                'senha' => 'string|min:8|confirmed'
            ]);
            $usuario->senha = $request->senha; // Mutator cuida do hash
        }

        $usuario->save();

        return redirect()->route('usuarios.index')->with('success', 'Usuário atualizado com sucesso.');
    }

    // Altera status de ativo/inativo
    public function toggleStatus(Request $request, $id)
    {
        // Valida o campo 'ativo' para garantir que seja um booleano
        $request->validate(['ativo' => 'required|boolean']);

        // Encontra o usuário pelo ID
        $usuario = Usuario::findOrFail($id);

        // Atualiza o status de ativo/inativo
        $usuario->ativo = $request->ativo;

        // Salva a alteração
        $usuario->save();

        // Redireciona com mensagem de sucesso
        return redirect()->route('usuarios.index')->with('success', 'Status do usuário atualizado com sucesso.');
    }

    // Reseta senha para a matrícula
    public function resetSenha($id)
    {
        $usuario = Usuario::findOrFail($id);
        $novaSenha = $usuario->matricula;

        $usuario->senha = $novaSenha; // Mutator aplica o hash
        $usuario->save();

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Senha redefinida para: ' . $usuario->nome)
            ->with('novaSenha', $novaSenha)
            ->with('usuarioId', $usuario->id);
    }

    // Formulário de login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Processa login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'matricula' => 'required|string',
            'senha' => 'required|string',
        ]);

        if (Auth::attempt([
            'matricula' => $credentials['matricula'],
            'senha' => $credentials['senha'],
            'ativo' => true,
        ])) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'matricula' => 'Matrícula ou senha incorretos, ou usuário inativo.',
        ])->onlyInput('matricula');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
