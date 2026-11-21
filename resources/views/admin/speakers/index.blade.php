@extends('layouts.app')

@section('title', 'Palestrantes e Instrutores')

@section('content')
    <h2>Palestrantes e Instrutores</h2>

    @if (session('success'))
        <div role="alert">{{ session('success') }}</div>
    @endif

    <p><a href="{{ route('speakers.create') }}">Cadastrar palestrante/instrutor</a></p>

    @if ($speakers->count())
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Instituição</th>
                    <th>Formação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($speakers as $speaker)
                    <tr>
                        <td><a href="{{ route('speakers.show', $speaker) }}">{{ $speaker->name }}</a></td>
                        <td>{{ $speaker->institution ?: 'Não informada' }}</td>
                        <td>{{ $speaker->academic_title ?: 'Não informada' }}</td>
                        <td>
                            <a href="{{ route('speakers.edit', $speaker) }}">Editar</a>
                            <form action="{{ route('speakers.destroy', $speaker) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Tem certeza que deseja excluir este palestrante/instrutor?')">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $speakers->links() }}
    @else
        <p>Nenhum palestrante ou instrutor cadastrado.</p>
    @endif
@endsection
