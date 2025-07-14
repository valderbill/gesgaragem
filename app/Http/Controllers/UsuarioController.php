<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Perfil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::with(['perfil', 'criador', 'ativadoPor', 'inativadoPor'])->get();
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
            'ativo' => 'sometimes|boolean',
        ]);

        $usuario = new Usuario();
        $usuario->nome = $request->nome;
        $usuario->matricula = $request->matricula;
        $usuario->password = $request->password; 
        $usuario->perfil_id = $request->perfil_id;
        $usuario->ativo = $request->has('ativo') ? true : false;
        $usuario->criado_por_id = Auth::id();

        if ($usuario->ativo) {
            $usuario->data_ativacao = now();
            $usuario->ativado_por_id = Auth::id();
        }

        $usuario->save();

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
            'ativo' => 'sometimes|boolean',
            'password' => 'nullable|string|min:6',
        ]);

        $usuario->nome = $request->nome;
        $usuario->matricula = $request->matricula;
        $usuario->perfil_id = $request->perfil_id;

        // Verifica mudança de status
        $novoStatus = $request->has('ativo') ? true : false;
        if ($usuario->ativo !== $novoStatus) {
            $usuario->ativo = $novoStatus;

            if ($novoStatus) {
                // Ativado
                $usuario->data_ativacao = now();
                $usuario->ativado_por_id = Auth::id();

                // Limpar inativação
                $usuario->data_inativacao = null;
                $usuario->inativado_por_id = null;
            } else {
                // Inativado
                $usuario->data_inativacao = now();
                $usuario->inativado_por_id = Auth::id();

                // Limpar ativação
                $usuario->data_ativacao = null;
                $usuario->ativado_por_id = null;
            }
        }

        if ($request->filled('password')) {
            $usuario->password = $request->password; 
        }

        $usuario->save();

        return redirect()->route('usuarios.index')->with('success', 'Usuário atualizado com sucesso.');
    }

    public function alternarStatus(Request $request, Usuario $usuario)
    {
        $request->validate(['ativo' => 'required|boolean']);

        $novoStatus = $request->ativo;
        if ($usuario->ativo !== $novoStatus) {
            $usuario->ativo = $novoStatus;

            if ($novoStatus) {
                $usuario->data_ativacao = now();
                $usuario->ativado_por_id = Auth::id();
                $usuario->data_inativacao = null;
                $usuario->inativado_por_id = null;
            } else {
                $usuario->data_inativacao = now();
                $usuario->inativado_por_id = Auth::id();
                $usuario->data_ativacao = null;
                $usuario->ativado_por_id = null;
            }

            $usuario->save();
        }

        $status = $usuario->ativo ? 'ativado' : 'inativado';

        return redirect()->route('usuarios.index')->with('success', "Usuário {$status} com sucesso.");
    }

    public function resetSenha($id)
    {
        $usuario = Usuario::findOrFail($id);
        $novaSenha = $usuario->matricula;

        $usuario->password = $novaSenha; 
        $usuario->save();

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Senha redefinida com sucesso para o usuário: ' . $usuario->nome)
            ->with('novaSenha', $novaSenha)
            ->with('usuarioId', $usuario->id);
    }
}
