@extends('layouts.app')

@section('content')
    <h1>Regras de inscrição</h1>
    <p><strong>{{ $edicao->nome }} ({{ $edicao->ano }})</strong></p>
    <p>Evento: {{ $edicao->data_inicio->format('d/m/Y') }} a {{ $edicao->data_fim->format('d/m/Y') }}</p>

    @if ($errors->any())
        <div role="alert">
            <p>Revise os campos informados:</p>
            <ul>@foreach ($errors->all() as $erro)<li>{{ $erro }}</li>@endforeach</ul>
        </div>
    @endif

    @include('edicoes._mensagens')

    <form method="POST" action="{{ route('edicoes.regras-inscricao.atualizar', $edicao) }}">
        @csrf
        @method('PUT')

        <fieldset>
            <legend>Alunos da Fatec</legend>
            <p><label>Início das inscrições<br><input type="datetime-local" name="inscricao_interna_inicio" value="{{ old('inscricao_interna_inicio', $edicao->inscricao_interna_inicio?->format('Y-m-d\\TH:i')) }}"></label></p>
            <p><label>Fim das inscrições<br><input type="datetime-local" name="inscricao_interna_fim" value="{{ old('inscricao_interna_fim', $edicao->inscricao_interna_fim?->format('Y-m-d\\TH:i')) }}"></label></p>
        </fieldset>

        <fieldset>
            <legend>Participantes externos</legend>
            <p><label><input type="checkbox" name="permite_inscricao_externa" value="1" @checked(old('permite_inscricao_externa', $edicao->permite_inscricao_externa))> Permitir inscrições externas</label></p>
            <p><label>Início das inscrições<br><input type="datetime-local" name="inscricao_externa_inicio" value="{{ old('inscricao_externa_inicio', $edicao->inscricao_externa_inicio?->format('Y-m-d\\TH:i')) }}"></label></p>
            <p><label>Fim das inscrições<br><input type="datetime-local" name="inscricao_externa_fim" value="{{ old('inscricao_externa_fim', $edicao->inscricao_externa_fim?->format('Y-m-d\\TH:i')) }}"></label></p>
        </fieldset>

        <fieldset>
            <legend>Limites e valor</legend>
            <p><label>Limite de palestras por inscrição<br><input type="number" min="0" required name="limite_palestras" value="{{ old('limite_palestras', $edicao->limite_palestras ?? 0) }}"></label></p>
            <p><label>Limite de minicursos por inscrição<br><input type="number" min="0" required name="limite_minicursos" value="{{ old('limite_minicursos', $edicao->limite_minicursos ?? 0) }}"></label></p>
            <p><label>Valor único da inscrição (R$)<br><input type="number" min="0" step="0.01" required name="valor_inscricao" value="{{ old('valor_inscricao', $edicao->valor_inscricao ?? '0.00') }}"></label></p>
        </fieldset>

        <button type="submit">Salvar regras</button>
        <a href="{{ route('edicoes.show', $edicao) }}">Voltar à edição</a>
    </form>
@endsection
