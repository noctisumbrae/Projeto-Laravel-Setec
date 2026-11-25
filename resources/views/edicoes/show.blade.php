@extends('layouts.app')

@section('content')
    <h1>{{ $edicao->nome }} ({{ $edicao->ano }})</h1>

    @include('edicoes._mensagens')

    <p><strong>Status:</strong> {{ ucfirst($edicao->status) }}</p>
    <p><strong>Período:</strong> {{ $edicao->data_inicio->format('d/m/Y') }} a {{ $edicao->data_fim->format('d/m/Y') }}</p>
    <p><strong>Descrição:</strong><br>{{ $edicao->descricao ?: 'Sem descrição.' }}</p>

    <div style="display:flex;gap:8px;align-items:center;">
        @unless ($edicao->estaEncerrada())
            <a href="{{ route('edicoes.edit', $edicao) }}">Editar</a>
        @endunless

        @if (! $edicao->estaAtiva() && ! $edicao->estaEncerrada())
            <form method="POST" action="{{ route('edicoes.ativar', $edicao) }}">
                @csrf
                @method('PATCH')
                <button type="submit">Ativar edição</button>
            </form>
        @endif

        @if ($edicao->estaAtiva())
            <form method="POST" action="{{ route('edicoes.encerrar', $edicao) }}"
                  onsubmit="return confirm('Encerrar esta edição? Esta ação não pode ser desfeita.');">
                @csrf
                @method('PATCH')
                <button type="submit">Encerrar edição</button>
            </form>
        @endif

        <a href="{{ route('edicoes.index') }}">Voltar</a>
    </div>
@endsection