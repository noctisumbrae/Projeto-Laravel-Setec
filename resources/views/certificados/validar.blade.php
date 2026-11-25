@extends('layouts.app')

@section('title', 'Validar certificado')

@section('content')
    <div class="validacao-pagina cartao cartao--estreito">
        <p class="texto-suave">Autenticidade do documento</p>
        <h1>Validar certificado</h1>
        <p>Informe o código de autenticidade para verificar um certificado da SETEC.</p>

        <form class="validacao-form" method="GET" action="{{ route('certificados.validar') }}">
            <div class="campo">
                <label for="codigo">Código de validação</label>
                <input
                    type="text"
                    id="codigo"
                    name="codigo"
                    value="{{ is_string(request()->query('codigo')) ? request()->query('codigo') : '' }}"
                    placeholder="Digite o código UUID"
                    required
                >
            </div>

            <button type="submit" class="botao">Validar certificado</button>
        </form>

        @if($mensagem)
            <div class="alerta alerta--erro mt" role="alert">{{ $mensagem }}</div>
        @endif

        @if($certificado)
            <div class="alerta alerta--sucesso mt" role="status">
                <h2>Certificado válido</h2>
                <p><strong>Participante:</strong> {{ $certificado->participante }}</p>
                <p><strong>Edição:</strong> {{ $certificado->edicao }}</p>
                <p><strong>Carga horária:</strong> {{ $certificado->carga_formatada }}</p>
                <p><strong>Data de emissão:</strong> {{ $certificado->data_formatada }}</p>
            </div>
        @endif
    </div>
@endsection
