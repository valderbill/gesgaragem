<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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

        // Tenta autenticar com os campos corretos
        if (Auth::attempt([
            'matricula' => $credentials['matricula'],
            'password' => $credentials['senha']
        ], $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended('/dashboard'); // Altere para sua rota de destino
        }

        // Falha na autenticação
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
