
@extends('layouts.app')

@section('title', 'Certificados')

@section('content')
    <h2>Módulo de Certificados</h2>

    <p>Protótipo da Equipe 5 - Dados fictícios.</p>

    <h3>Participante: Ana Souza (exemplo)</h3>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>Atividade</th>
                <th>Duração</th>
                <th>Presença</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($atividades as $atividade)
                <tr>
                    <td>{{ $atividade['titulo'] }}</td>
                    <td>
                        {{ intdiv($atividade['duracao_minutos'], 60) }}h
                        {{ $atividade['duracao_minutos'] % 60 }}min
                    </td>
                    <td>
                        {{ $atividade['presenca_confirmada']
                            ? 'Confirmada' : 'Ausente' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>
        Carga horária válida:
        {{ intdiv($totalMinutos, 60) }}h
        {{ $totalMinutos % 60 }}min
    </h3>

    <p>
        O cálculo considera apenas as atividades
        com presença confirmada.
    </p>
@endsection
