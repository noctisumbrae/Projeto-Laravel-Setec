<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\Cpf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * CONTROLLER - Cadastro do participante e atualização do próprio perfil.
 */
class ParticipanteController extends Controller
{
    // ---------- Cadastro (visitante) ----------

    public function create()
    {
        return view('participantes.create');
    }

    public function store(Request $request)
    {
        $this->limparMascaras($request);

        $dados = $request->validate([
            'name'              => ['required', 'string', 'min:3', 'max:255'],
            'email'             => ['required', 'email', 'max:255', 'unique:users,email'],
            'cpf'               => ['required', new Cpf(), 'unique:users,cpf'],
            'telefone'          => ['required', 'digits_between:10,11'],
            'tipo_participante' => ['required', Rule::in([User::TIPO_ALUNO, User::TIPO_EXTERNO])],
            'ra'                => ['nullable', 'required_if:tipo_participante,' . User::TIPO_ALUNO, 'string', 'max:20'],
            'password'          => ['required', 'confirmed', 'min:8'],
        ]);

        if ($dados['tipo_participante'] === User::TIPO_EXTERNO) {
            $dados['ra'] = null;
        }

        $dados['perfil'] = User::PERFIL_PARTICIPANTE; // nunca vem do formulário

        $user = User::create($dados);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')
            ->with('sucesso', 'Cadastro realizado! Agora você já pode fazer a inscrição na edição atual.');
    }

    // ---------- Perfil (participante logado) ----------

    public function edit()
    {
        $user = Auth::user();
        $podeAlterarTipo = ! $user->inscricoes()->exists();

        return view('participantes.edit', compact('user', 'podeAlterarTipo'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $podeAlterarTipo = ! $user->inscricoes()->exists();

        $this->limparMascaras($request);

        // Campos travados na tela (disabled) não são enviados pelo navegador.
        if (! $podeAlterarTipo) {
            $request->merge(['tipo_participante' => $user->tipo_participante, 'ra' => $user->ra]);
        }

        $dados = $request->validate([
            'name'              => ['required', 'string', 'min:3', 'max:255'],
            'email'             => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'telefone'          => ['required', 'digits_between:10,11'],
            'tipo_participante' => ['required', Rule::in([User::TIPO_ALUNO, User::TIPO_EXTERNO])],
            'ra'                => ['nullable', 'required_if:tipo_participante,' . User::TIPO_ALUNO, 'string', 'max:20'],
            'senha_atual'       => ['nullable', 'required_with:password', 'current_password'],
            'password'          => ['nullable', 'confirmed', 'min:8'],
        ]);

        // Depois de inscrito, o tipo não pode mudar (evita burlar os períodos de inscrição).
        if ($dados['tipo_participante'] === User::TIPO_EXTERNO) {
            $dados['ra'] = null;
        }

        $user->fill([
            'name'              => $dados['name'],
            'email'             => $dados['email'],
            'telefone'          => $dados['telefone'],
            'tipo_participante' => $dados['tipo_participante'],
            'ra'                => $dados['ra'],
        ]);

        if (! empty($dados['password'])) {
            $user->password = $dados['password'];
        }

        $user->save();

        return redirect()->route('perfil.edit')->with('sucesso', 'Dados atualizados com sucesso!');
    }

    /** Guarda CPF e telefone apenas com dígitos. */
    private function limparMascaras(Request $request): void
    {
        $request->merge([
            'cpf'      => preg_replace('/\D/', '', (string) $request->input('cpf')),
            'telefone' => preg_replace('/\D/', '', (string) $request->input('telefone')),
        ]);
    }
}
