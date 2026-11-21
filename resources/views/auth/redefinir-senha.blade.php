@extends('layouts.app')

@section('title', 'Nova senha')

@section('content')
    <div class="cartao cartao--estreito">
        <h2>Criar nova senha</h2>

        <form action="{{ route('password.update') }}" method="POST" novalidate>
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="campo {{ $errors->has('email') ? 'campo--erro' : '' }}">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required>
                @error('email') <div class="campo__erro">{{ $message }}</div> @enderror
            </div>

            <div class="campo {{ $errors->has('password') ? 'campo--erro' : '' }}">
                <label for="password">Nova senha</label>
                <input type="password" id="password" name="password" autocomplete="new-password" required>
                <div class="campo__ajuda">Mínimo de 8 caracteres.</div>
                @error('password') <div class="campo__erro">{{ $message }}</div> @enderror
            </div>

            <div class="campo">
                <label for="password_confirmation">Confirmar nova senha</label>
                <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" required>
            </div>

            <button type="submit" class="botao">Salvar nova senha</button>
        </form>
    </div>
@endsection
