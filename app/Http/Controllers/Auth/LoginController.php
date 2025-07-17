<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\Usuario;

class LoginController extends Controller
{
    /**
     * Exibe o formulário de login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Realiza a autenticação.
     */
    public function login(Request $request)
    {
        // Validação dos campos do formulário
        $credentials = $request->validate([
            'matricula' => ['required', 'string'],
            'senha' => ['required', 'string'],
        ]);

        // Busca o usuário pela matrícula
        $user = Usuario::where('matricula', $credentials['matricula'])->first();

        // Verifica se o usuário existe
        if (!$user) {
            throw ValidationException::withMessages([
                'matricula' => __('Matrícula não encontrada.'),
            ]);
        }

        // Verifica se o usuário está ativo
        if (!$user->ativo) {
            throw ValidationException::withMessages([
                'matricula' => __('Usuário inativo. Procure a administração.'),
            ]);
        }

        // Verifica se a senha está correta usando Hash::check
        if (!\Illuminate\Support\Facades\Hash::check($credentials['senha'], $user->senha)) {
            throw ValidationException::withMessages([
                'senha' => __('Senha incorreta.'),
            ]);
        }

        // Faz login manualmente, pois o campo da senha é personalizado
        Auth::login($user, $request->boolean('remember'));

        // Regenera a sessão para segurança
        $request->session()->regenerate();

        // Redireciona para a página inicial ou dashboard
        return redirect()->intended('/dashboard');
    }

    /**
     * Realiza o logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
