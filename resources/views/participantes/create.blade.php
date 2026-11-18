@extends('layouts.app')

@section('title', 'Criar conta')

@section('content')
    <div class="cartao cartao--estreito cartao--form"">
        <h2>Criar conta de participante</h2>
        <p class="texto-suave">O cadastro vale para todas as edições. A inscrição é feita depois, em cada edição.</p>

        <form action="{{ route('participantes.store') }}" method="POST" novalidate>
            @csrf

            @include('participantes._campos')

            <div class="campo {{ $errors->has('cpf') ? 'campo--erro' : '' }}">
                <label for="cpf">CPF</label>
                <input type="text" id="cpf" name="cpf" value="{{ old('cpf') }}" placeholder="000.000.000-00" inputmode="numeric" required>
                @error('cpf') <div class="campo__erro">{{ $message }}</div> @enderror
            </div>

            <div class="dois">
                <div class="campo {{ $errors->has('password') ? 'campo--erro' : '' }}">
                    <label for="password">Senha</label>
                    <input type="password" id="password" name="password" autocomplete="new-password" required>
                    <div class="campo__ajuda">Mínimo de 8 caracteres.</div>
                    @error('password') <div class="campo__erro">{{ $message }}</div> @enderror
                </div>

                <div class="campo">
                    <label for="password_confirmation">Confirmar senha</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" required>
                </div>
            </div>

            <button type="submit" class="botao">Criar conta</button>
            <a class="botao botao--claro" href="{{ route('home') }}">Cancelar</a>
        </form>
    </div>
@endsection
