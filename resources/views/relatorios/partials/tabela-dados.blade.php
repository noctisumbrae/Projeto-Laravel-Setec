<div class="overflow-x-auto bg-white border rounded-lg">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-100">
            <tr>
                @foreach ($colunas as $coluna)
                    <th class="px-5 py-3 text-left text-sm font-semibold">
                        {{ $coluna['titulo'] }}
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse ($linhas as $linha)
                <tr class="hover:bg-gray-50">
                    @foreach ($colunas as $coluna)
                        <td class="px-5 py-3 text-sm text-gray-700">
                            {{ data_get($linha, $coluna['campo']) ?? '—' }}
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($colunas) }}"
                        class="px-5 py-8 text-center text-gray-500">

                        Nenhum registro encontrado.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>