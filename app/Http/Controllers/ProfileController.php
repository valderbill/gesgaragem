<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Formulário de edição do perfil.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'usuario' => $request->user(),
        ]);
    }

    /**
     * Formulário para alteração de senha.
     */
    public function editSenha(): View
    {
        return view('profile.edit-senha');
    }

    /**
     * Atualiza os dados do perfil (exceto senha).
     */
    public function update(Request $request)
    {
        $usuario = $request->user();

        $request->validate([
            'matricula' => ['required', 'string', 'max:255', 'unique:usuarios,matricula,' . $usuario->id],
            'nome' => ['required', 'string', 'max:255'],
        ]);

        $usuario->matricula = $request->matricula;
        $usuario->nome = $request->nome;
        $usuario->save();

        return Redirect::route('profile.edit')->with('status', 'perfil-atualizado');
    }

    /**
     * Atualiza a senha do usuário.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'senha_atual' => ['required', 'string'],
            'nova_senha' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $usuario = $request->user();

        if (!Hash::check($request->senha_atual, $usuario->senha)) {
            return back()->withErrors(['senha_atual' => 'Senha atual incorreta.']);
        }

        $usuario->senha = Hash::make($request->nova_senha);
        $usuario->save();

        return back()->with('status', 'senha-atualizada');
    }

    /**
     * Exclui a conta do usuário.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'senha' => ['required', 'string'],
        ]);

        $usuario = $request->user();

        if (!Hash::check($request->senha, $usuario->senha)) {
            return back()->withErrors(['senha' => 'Senha incorreta.']);
        }

        Auth::logout();
        $usuario->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
