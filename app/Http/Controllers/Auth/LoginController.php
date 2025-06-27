<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\Usuario; // Certifique-se de importar seu model de usuário

class LoginController extends Controller
{
    /**
     * Exibe o formulário de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Realiza a autenticação
     */
    public function login(Request $request)
    {
        // Validação dos campos
        $credentials = $request->validate([
            'matricula' => ['required', 'string'],
            'senha' => ['required', 'string'],
        ]);

        // Busca o usuário pelo campo matrícula
        $user = Usuario::where('matricula', $credentials['matricula'])->first();

        // Verifica se o usuário existe
        if (!$user) {
            throw ValidationException::withMessages([
                'matricula' => __('Matrícula ou senha inválida.'),
            ]);
        }

        // Verifica se o usuário está ativo
        if (!$user->ativo) {
            throw ValidationException::withMessages([
                'matricula' => __('Usuário inativo. Procure a administração.'),
            ]);
        }

        // Tenta autenticar com os campos corretos
        if (Auth::attempt([
            'matricula' => $credentials['matricula'],
            'password' => $credentials['senha']
        ], $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard'); // Altere para sua rota de destino
        }

        // Senha incorreta
        throw ValidationException::withMessages([
            'matricula' => __('Matrícula ou senha inválida.'),
        ]);
    }

    /**
     * Realiza o logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
