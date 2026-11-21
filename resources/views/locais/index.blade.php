
@extends('layouts.app')

@section('content')
<main class="container">
    <h1>Gerenciamento de Locais</h1>

    <p>Salas, laboratórios e auditórios da SETEC.</p>

    <a href="{{ route('locais.create') }}">
        Cadastrar Novo Local
    </a>

    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Tipo</th>
                <th>Capacidade</th>
                <th>Ações</th>
            </tr>
        </thead>

        <tbody>
            @forelse($locais as $local)
                <tr>
                    <td>{{ $local->nome }}</td>
                    <td>{{ ucfirst($local->tipo) }}</td>
                    <td>{{ $local->capacidade }} pessoas</td>
                    <td>
                        <a href="{{ route('locais.show', $local) }}">
                            Visualizar
                        </a>

                        <a href="{{ route('locais.edit', $local) }}">
                            Editar
                        </a>

                        <form
                            action="{{ route('locais.destroy', $local) }}"
                            method="POST"
                        >
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
                    <td colspan="4">
                        Nenhum local cadastrado.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $locais->links() }}
</main>
@endsection
