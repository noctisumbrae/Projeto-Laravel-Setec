<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

/**
 * CONTROLLER - Recuperação de senha.
 * Em desenvolvimento (MAIL_MAILER=log) o link enviado aparece em storage/logs/laravel.log.
 */
class RecuperacaoSenhaController extends Controller
{
    public function formularioEsqueci()
    {
        return view('auth.esqueci-senha');
    }

    public function enviarLink(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('sucesso', 'Enviamos o link de redefinição para o seu e-mail.')
            : back()->withInput($request->only('email'))->withErrors(['email' => __($status)]);
    }

    public function formularioRedefinir(Request $request, string $token)
    {
        return view('auth.redefinir-senha', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function redefinir(Request $request)
    {
        $dados = $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $senha) {
                $user->forceFill([
                    'password'       => $senha, // o cast "hashed" do model faz o hash
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('sucesso', 'Senha redefinida! Entre com a nova senha.')
            : back()->withInput($request->only('email'))->withErrors(['email' => __($status)]);
    }
}
