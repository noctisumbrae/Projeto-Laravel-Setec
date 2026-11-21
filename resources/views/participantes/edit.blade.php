@extends('layouts.app')

@section('title', 'Meu perfil')

@section('content')
    <div class="cartao cartao--estreito cartao--form"">
        <h2>Meu perfil</h2>

        <p class="texto-suave">
            CPF: <strong>{{ $user->cpf_formatado }}</strong> (não pode ser alterado).
            Para mudar o CPF, procure a organização do evento.
        </p>

        <form action="{{ route('perfil.update') }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            @include('participantes._campos')

            <h3 class="mt">Alterar senha <small class="texto-suave">(opcional)</small></h3>

            <div class="campo {{ $errors->has('senha_atual') ? 'campo--erro' : '' }}">
                <label for="senha_atual">Senha atual</label>
                <input type="password" id="senha_atual" name="senha_atual" autocomplete="current-password">
                @error('senha_atual') <div class="campo__erro">{{ $message }}</div> @enderror
            </div>

            <div class="dois">
                <div class="campo {{ $errors->has('password') ? 'campo--erro' : '' }}">
                    <label for="password">Nova senha</label>
                    <input type="password" id="password" name="password" autocomplete="new-password">
                    @error('password') <div class="campo__erro">{{ $message }}</div> @enderror
                </div>
                <div class="campo">
                    <label for="password_confirmation">Confirmar nova senha</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password">
                </div>
            </div>

            <button type="submit" class="botao">Salvar alterações</button>
        </form>
    </div>
@endsection
