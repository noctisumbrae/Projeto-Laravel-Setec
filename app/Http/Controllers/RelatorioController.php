<?php
namespace App\Http\Controllers;
use App\Models\Atividade;
use App\Models\Inscricao;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RelatorioController extends Controller
{
    public function index(): View
    {
        return view('relatorios.index');
    }
    
    public function inscricoes(Request $request): View|StreamedResponse
    {
        $dados = Inscricao::with(['user', 'atividades'])
            ->orderByDesc('created_at')
            ->get()
            ->map(function (Inscricao $inscricao) {

                $atividades = $inscricao->atividades
                    ->pluck('titulo')
                    ->implode(', ');

                return [
                    'participante' => $inscricao->user?->name ?? '-',

                    'email' => $inscricao->user?->email ?? '-',

                    'atividade' => $atividades !== ''
                        ? $atividades
                        : 'Nenhuma atividade selecionada',

                    'status' => ucfirst($inscricao->status),

                    'data' => $inscricao->created_at
                        ? $inscricao->created_at->format('d/m/Y')
                        : '-',
                ];
            })
            ->all();

        return $this->renderizarTabela(
            $request,
            'Relatório de inscrições',
            'Consulte as inscrições dos participantes cadastradas no MySQL.',
            [
                ['titulo' => 'Participante', 'campo' => 'participante'],
                ['titulo' => 'E-mail', 'campo' => 'email'],
                ['titulo' => 'Atividade', 'campo' => 'atividade'],
                ['titulo' => 'Situação', 'campo' => 'status'],
                ['titulo' => 'Data', 'campo' => 'data'],
            ],
            $dados,
            ['participante', 'email']
        );
    }
    
    public function atividades(Request $request): View|StreamedResponse
    {
        $linhas = Atividade::with(['edicao', 'local'])
            ->orderBy('inicio')
            ->get()
            ->map(function (Atividade $atividade) {
                return [
                    'atividade' => $atividade->titulo,
                    'tipo' => $atividade->tipo,
                    'responsavel' => $atividade->responsavel,
                    'edicao' => $atividade->edicao?->nome ?? '-',
                    'local' => $atividade->local?->nome ?? '-',
                    'data' => $atividade->inicio?->format('d/m/Y H:i') ?? '-',
                    'duracao' => $atividade->duracao_minutos . ' min',
                    'capacidade' => $atividade->capacidade,
                ];
            })
            ->all();

        return $this->renderizarTabela(
            $request,
            'Relatório de atividades',
            'Consulte as atividades cadastradas no MySQL.',
            [
                ['titulo' => 'Atividade', 'campo' => 'atividade'],
                ['titulo' => 'Tipo', 'campo' => 'tipo'],
                ['titulo' => 'Responsável', 'campo' => 'responsavel'],
                ['titulo' => 'Edição', 'campo' => 'edicao'],
                ['titulo' => 'Local', 'campo' => 'local'],
                ['titulo' => 'Data', 'campo' => 'data'],
                ['titulo' => 'Duração', 'campo' => 'duracao'],
                ['titulo' => 'Capacidade', 'campo' => 'capacidade'],
            ],
            $linhas
        );
    }

    public function presencas(Request $request): View|StreamedResponse
    {
        $dados = DB::table('inscricao_atividade as ia')
            ->join('inscricoes as i', 'i.id', '=', 'ia.inscricao_id')
            ->join('users as u', 'u.id', '=', 'i.user_id')
            ->join('atividades as a', 'a.id', '=', 'ia.atividade_id')
            ->join('edicoes as e', 'e.id', '=', 'i.edicao_id')
            ->leftJoin('presencas as p', 'p.inscricao_atividade_id', '=', 'ia.id')
            ->select(
                'u.name as participante',
                'u.email',
                'a.titulo as atividade',
                'e.nome as edicao',
                'p.id as presenca_id',
                'p.presente',
                'p.registrada_em'
            )
            ->orderBy('u.name')
            ->get()
            ->map(function ($registro) {

                if ($registro->presenca_id === null) {
                    $situacao = 'Não registrada';
                } elseif ($registro->presente) {
                    $situacao = 'Presente';
                } else {
                    $situacao = 'Ausente';
                }

                return [
                    'participante' => $registro->participante,
                    'email' => $registro->email,
                    'atividade' => $registro->atividade,
                    'edicao' => $registro->edicao,
                    'situacao' => $situacao,
                    'data' => $registro->registrada_em
                        ? \Carbon\Carbon::parse($registro->registrada_em)
                            ->format('d/m/Y H:i')
                        : '-',
                ];
            })
            ->all();

        return $this->renderizarTabela(
            $request,
            'Relatório de presenças',
            'Acompanhe o comparecimento dos participantes nas atividades.',
            [
                ['titulo' => 'Participante', 'campo' => 'participante'],
                ['titulo' => 'E-mail', 'campo' => 'email'],
                ['titulo' => 'Atividade', 'campo' => 'atividade'],
                ['titulo' => 'Edição', 'campo' => 'edicao'],
                ['titulo' => 'Presença', 'campo' => 'situacao'],
                ['titulo' => 'Registro', 'campo' => 'data'],
            ],
            $dados
        );
    }

    
    public function cargaHoraria(Request $request): View|StreamedResponse
    {
        $dados = DB::table('inscricoes as i')
            ->join('users as u', 'u.id', '=', 'i.user_id')
            ->join('edicoes as e', 'e.id', '=', 'i.edicao_id')
            ->leftJoin('inscricao_atividade as ia', 'ia.inscricao_id', '=', 'i.id')
            ->leftJoin('atividades as a', 'a.id', '=', 'ia.atividade_id')
            ->leftJoin('presencas as p', 'p.inscricao_atividade_id', '=', 'ia.id')
            ->select(
                'i.id',
                'u.name as participante',
                'u.email',
                'e.nome as edicao'
            )
            ->selectRaw("
                SUM(
                    CASE
                        WHEN p.presente = 1
                        AND ia.status = 'confirmada'
                        AND i.status = 'confirmada'
                        AND a.edicao_id = i.edicao_id
                        THEN a.duracao_minutos
                        ELSE 0
                    END
                ) as minutos
            ")
            ->selectRaw("
                SUM(
                    CASE
                        WHEN p.presente = 1
                        AND ia.status = 'confirmada'
                        AND i.status = 'confirmada'
                        AND a.edicao_id = i.edicao_id
                        THEN 1
                        ELSE 0
                    END
                ) as atividades_presentes
            ")
            ->groupBy('i.id', 'u.name', 'u.email', 'e.nome')
            ->orderBy('u.name')
            ->get()
            ->map(function ($registro) {

                $minutos = (int) $registro->minutos;

                $horas = intdiv($minutos, 60);
                $minutosRestantes = $minutos % 60;

                return [
                    'participante' => $registro->participante,
                    'email' => $registro->email,
                    'edicao' => $registro->edicao,
                    'atividades' => (int) $registro->atividades_presentes,
                    'carga_horaria' => sprintf(
                        '%dh %02dmin',
                        $horas,
                        $minutosRestantes
                    ),
                ];
            })
            ->all();

        return $this->renderizarTabela(
            $request,
            'Relatório de carga horária',
            'Total de horas das atividades com presença registrada e inscrições confirmadas.',
            [
                ['titulo' => 'Participante', 'campo' => 'participante'],
                ['titulo' => 'E-mail', 'campo' => 'email'],
                ['titulo' => 'Edição', 'campo' => 'edicao'],
                ['titulo' => 'Atividades presentes', 'campo' => 'atividades'],
                ['titulo' => 'Carga horária', 'campo' => 'carga_horaria'],
            ],
            $dados
        );
    }

    public function certificados(Request $request): View|StreamedResponse
    {
        $dados = DB::table('certificados as c')
            ->join('inscricoes as i', 'i.id', '=', 'c.inscricao_id')
            ->join('users as u', 'u.id', '=', 'i.user_id')
            ->join('edicoes as e', 'e.id', '=', 'i.edicao_id')
            ->select(
                'u.name as participante',
                'u.email',
                'e.nome as edicao',
                'c.codigo_validacao',
                'c.carga_horaria_minutos',
                'c.emitido_em',
                'c.status'
            )
            ->orderByDesc('c.emitido_em')
            ->get()
            ->map(function ($registro) {

                $minutos = (int) $registro->carga_horaria_minutos;

                return [
                    'participante' => $registro->participante,
                    'email' => $registro->email,
                    'edicao' => $registro->edicao,
                    'carga_horaria' => sprintf(
                        '%dh %02dmin',
                        intdiv($minutos, 60),
                        $minutos % 60
                    ),
                    'codigo' => $registro->codigo_validacao,
                    'status' => ucfirst($registro->status),
                    'emissao' => \Carbon\Carbon::parse(
                        $registro->emitido_em
                    )->format('d/m/Y H:i'),
                ];
            })
            ->all();

        return $this->renderizarTabela(
            $request,
            'Relatório de certificados',
            'Consulte os certificados emitidos e seus códigos de validação.',
            [
                ['titulo' => 'Participante', 'campo' => 'participante'],
                ['titulo' => 'E-mail', 'campo' => 'email'],
                ['titulo' => 'Edição', 'campo' => 'edicao'],
                ['titulo' => 'Carga horária', 'campo' => 'carga_horaria'],
                ['titulo' => 'Código de validação', 'campo' => 'codigo'],
                ['titulo' => 'Situação', 'campo' => 'status'],
                ['titulo' => 'Emissão', 'campo' => 'emissao'],
            ],
            $dados
        );
    }
    
    public function ocupacao(Request $request): View|StreamedResponse
    {
        $dados = Atividade::with(['edicao', 'local'])
            ->withCount([
                'inscricoes as vagas_ocupadas' => function ($query) {
                    $query
                        ->where('inscricao_atividade.status', 'confirmada')
                        ->where('inscricoes.status', 'confirmada');
                }
            ])
            ->orderBy('inicio')
            ->get()
            ->map(function (Atividade $atividade) {

                $capacidade = (int) $atividade->capacidade;

                $ocupadas = (int) $atividade->vagas_ocupadas;

                $disponiveis = max(0, $capacidade - $ocupadas);

                $percentual = $capacidade > 0
                    ? number_format(
                        ($ocupadas / $capacidade) * 100,
                        1,
                        ',',
                        '.'
                    ) . '%'
                    : '—';

                return [
                    'atividade' => $atividade->titulo,
                    'edicao' => $atividade->edicao?->nome ?? '-',
                    'local' => $atividade->local?->nome ?? '-',
                    'capacidade' => $capacidade,
                    'ocupadas' => $ocupadas,
                    'disponiveis' => $disponiveis,
                    'percentual' => $percentual,
                ];
            })
            ->all();

        return $this->renderizarTabela(
            $request,
            'Relatório de ocupação',
            'Consulte a ocupação das atividades cadastradas no MySQL.',
            [
                ['titulo' => 'Atividade', 'campo' => 'atividade'],
                ['titulo' => 'Edição', 'campo' => 'edicao'],
                ['titulo' => 'Local', 'campo' => 'local'],
                ['titulo' => 'Capacidade', 'campo' => 'capacidade'],
                ['titulo' => 'Vagas confirmadas', 'campo' => 'ocupadas'],
                ['titulo' => 'Vagas disponíveis', 'campo' => 'disponiveis'],
                ['titulo' => 'Ocupação', 'campo' => 'percentual'],
            ],
            $dados
        );
    }

    private function renderizarTabela(
        Request $request,
        string $titulo,
        string $descricao,
        array $colunas,
        array $dados,
        ?array $camposBusca = null
    ): View|StreamedResponse {

        $linhas = collect($dados);
        $valorBusca = $request->query('busca', '');
        $busca = is_string($valorBusca)
            ? trim($valorBusca)
            : '';
        $camposBusca ??= array_column($colunas, 'campo');

        if ($busca !== '') {
            $termo = mb_strtolower($busca);
            $linhas = $linhas->filter(
                function (array $linha) use ($termo, $camposBusca): bool {
                    foreach ($camposBusca as $campo) {
                        $valor = (string) ($linha[$campo] ?? '');
                        if (str_contains(
                            mb_strtolower($valor),
                            $termo
                        )) {
                            return true;
                        }
                    }
                    return false;
                }
            )->values();
        }

        if ($request->query('exportar') === 'csv') {
            $nomeArquivo = Str::slug($titulo)
                . '-'
                . now()->format('Ymd-His')
                . '.csv';
            return response()->streamDownload(
                function () use ($colunas, $linhas) {
                    $arquivo = fopen('php://output', 'w');
                    if ($arquivo === false) {
                        throw new \RuntimeException(
                            'Não foi possível gerar o CSV.'
                        );
                    }
                    fwrite($arquivo, "\xEF\xBB\xBF");
                    fputcsv(
                        $arquivo,
                        array_column($colunas, 'titulo'),
                        ';',
                        '"',
                        ''
                    );

                    foreach ($linhas as $linha) {
                        $valores = [];
                        foreach ($colunas as $coluna) {
                            $campo = $coluna['campo'];
                            $valor = (string) ($linha[$campo] ?? '');
                            if (preg_match('/^\s*[=+\-@]/u', $valor)) {
                                $valor = "'" . $valor;
                            }
                            $valores[] = $valor;
                        }
                        fputcsv(
                            $arquivo,
                            $valores,
                            ';',
                            '"',
                            ''
                        );
                    }
                    fclose($arquivo);
                },
                $nomeArquivo,
                [
                    'Content-Type' => 'text/csv; charset=UTF-8',
                ]
            );
        }

        return view('relatorios.tabela', [
            'titulo' => $titulo,
            'descricao' => $descricao,
            'colunas' => $colunas,
            'linhas' => $linhas,
            'busca' => $busca,
        ]);
    }

}
