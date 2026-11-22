<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/** CONTROLLER - Login e logout do participante. */
class LoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $credenciais = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credenciais, $request->boolean('lembrar'))) {
            return back()
                ->withErrors(['email' => 'E-mail ou senha incorretos.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate(); // protege contra session fixation

        return redirect()->intended(route('inscricoes.index'));
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('sucesso', 'Você saiu do sistema.');
    }
}
