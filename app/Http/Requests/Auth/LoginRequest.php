<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a fazer esta requisição.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação da requisição.
     */
    public function rules(): array
    {
        return [
            'matricula' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Realiza a autenticação com base na matrícula e senha.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $credentials = $this->only('matricula', 'password');

        if (! Auth::attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'matricula' => trans('auth.failed'), // ou: 'As credenciais estão incorretas.'
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Garante que a requisição não excedeu o limite de tentativas.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'matricula' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Gera uma chave única para limitar tentativas por matrícula + IP.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('matricula')) . '|' . $this->ip());
    }
}
