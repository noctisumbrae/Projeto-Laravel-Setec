@extends('layouts.app')

@section('title', 'Recuperar senha')

@section('content')
    <div class="cartao cartao--estreito">
        <h2>Recuperar senha</h2>
        <p class="texto-suave">Informe o e-mail do cadastro. Enviaremos um link para criar uma nova senha.</p>

        <form action="{{ route('password.email') }}" method="POST" novalidate>
            @csrf
            <div class="campo {{ $errors->has('email') ? 'campo--erro' : '' }}">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email') <div class="campo__erro">{{ $message }}</div> @enderror
            </div>
            <button type="submit" class="botao">Enviar link</button>
            <a class="botao botao--claro" href="{{ route('login') }}">Voltar</a>
        </form>
    </div>
@endsection
