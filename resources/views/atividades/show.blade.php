
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Atividade</title>
</head>
<body>
    <h1>Detalhes da Atividade</h1>

    <p><strong>Título:</strong> {{ $atividade->titulo }}</p>
    <p><strong>Descrição:</strong> {{ $atividade->descricao }}</p>
    <p><strong>Tipo:</strong> {{ ucfirst($atividade->tipo) }}</p>
    <p><strong>Responsável:</strong> {{ $atividade->responsavel }}</p>
    <p><strong>Início:</strong> {{ $atividade->inicio?->format('d/m/Y H:i') ?? 'Não informado' }}</p>
    <p><strong>Duração:</strong> {{ $atividade->duracao_minutos }} minutos</p>
    <p><strong>Capacidade:</strong> {{ $atividade->capacidade }} pessoas</p>
    <p><strong>Local:</strong> {{ $atividade->local?->nome ?? 'Não informado' }}</p>
    <p><strong>Edição:</strong> {{ $atividade->edicao?->nome ?? 'Não informado' }}</p>

    <a href="{{ route('atividades.edit', $atividade) }}">Editar</a>
    <a href="{{ route('atividades.index') }}">Voltar</a>
</body>
</html>
