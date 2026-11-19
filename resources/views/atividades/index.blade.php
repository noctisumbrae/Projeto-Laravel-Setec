
@extends('layouts.app')

@section('content')
<main class="container">
    <h1>Gerenciamento de Atividades</h1>

    <p>Palestras e minicursos da SETEC.</p>

    <a href="{{ route('atividades.create') }}">
        Cadastrar Nova Atividade
    </a>

    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>Título</th>
                <th>Tipo</th>
                <th>Data</th>
                <th>Horário</th>
                <th>Local</th>
                <th>Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse($atividades as $atividade)
                <tr>
                    <td>{{ $atividade->titulo }}</td>
                    <td>{{ ucfirst($atividade->tipo) }}</td>
                    <td>{{ $atividade->data->format('d/m/Y') }}</td>
                    <td>{{ substr($atividade->horario, 0, 5) }}</td>
                    <td>{{ $atividade->local?->nome ?? 'Não informado' }}</td>
                    <td>
                        <a href="{{ route('atividades.show', $atividade) }}">
                            Visualizar
                        </a>

                        <a href="{{ route('atividades.edit', $atividade) }}">
                            Editar
                        </a>

                        <form action="{{ route('atividades.destroy', $atividade) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button type="submit">
                                Excluir
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        Nenhuma atividade cadastrada.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $atividades->links() }}
</main>
@endsection
