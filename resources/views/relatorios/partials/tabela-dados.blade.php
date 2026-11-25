<div class="tabela-wrap relatorio-tabela">
    <table>
        <thead>
            <tr>
                @foreach ($colunas as $coluna)
                    <th>{{ $coluna['titulo'] }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse ($linhas as $linha)
                <tr>
                    @foreach ($colunas as $coluna)
                        <td>{{ data_get($linha, $coluna['campo']) ?? '—' }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($colunas) }}" class="tabela-vazia">Nenhum registro encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
