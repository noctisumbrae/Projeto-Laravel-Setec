@extends('layouts.app')

@section('title', 'Entrar')

@section('content')
    <div class="cartao cartao--estreito">
        <h2>Entrar</h2>

        <form action="{{ route('login.store') }}" method="POST" novalidate>
            @csrf

            <div class="campo {{ $errors->has('email') ? 'campo--erro' : '' }}">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email" required autofocus>
                @error('email') <div class="campo__erro">{{ $message }}</div> @enderror
            </div>

            <div class="campo {{ $errors->has('password') ? 'campo--erro' : '' }}">
                <label for="password">Senha</label>
                <input type="password" id="password" name="password" autocomplete="current-password" required>
                @error('password') <div class="campo__erro">{{ $message }}</div> @enderror
            </div>

            <div class="campo campo--linha">
                <input type="checkbox" id="lembrar" name="lembrar" value="1">
                <label for="lembrar">Manter conectado</label>
            </div>

            <button type="submit" class="botao">Entrar</button>
        </form>

        <p class="mt"><a href="{{ route('password.request') }}">Esqueci minha senha</a></p>
        <p>Ainda não tem conta? <a href="{{ route('participantes.create') }}">Cadastre-se</a>.</p>
    </div>
@endsection
