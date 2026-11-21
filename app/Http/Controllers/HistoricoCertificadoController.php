<?php

namespace App\Http\Controllers;

use App\Services\EmissorCertificados;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class HistoricoCertificadoController extends Controller
{
    // Lista certificados e inscricoes disponiveis para emissao
    public function index(Request $request)
    {
        $usuarioId = $request->user()->id;

        $certificados = DB::table('certificados as c')
            ->join('inscricoes as i', 'i.id', '=', 'c.inscricao_id')
            ->join('edicoes as e', 'e.id', '=', 'i.edicao_id')
            ->where('i.user_id', $usuarioId)
            ->where('c.status', 'emitido')
            ->select(
                'c.id',
                'e.nome as edicao',
                'c.codigo_validacao',
                'c.carga_horaria_minutos',
                'c.emitido_em',
                'c.status'
            )
            ->orderByDesc('c.emitido_em')
            ->get();

        // Inscricoes confirmadas que ainda nao possuem certificado
        // e possuem pelo menos uma presenca com carga horaria valida.
        $inscricoesDisponiveis = DB::table('inscricoes as i')
            ->join('edicoes as e', 'e.id', '=', 'i.edicao_id')
            ->leftJoin(
                'certificados as c',
                'c.inscricao_id',
                '=',
                'i.id'
            )
            ->where('i.user_id', $usuarioId)
            ->where('i.status', 'confirmada')
            ->whereNull('c.id')
            ->whereExists(function ($query) {
                $query->selectRaw('1')
                    ->from('inscricao_atividade as ia')
                    ->join(
                        'atividades as a',
                        'a.id',
                        '=',
                        'ia.atividade_id'
                    )
                    ->join(
                        'presencas as p',
                        'p.inscricao_atividade_id',
                        '=',
                        'ia.id'
                    )
                    ->whereColumn('ia.inscricao_id', 'i.id')
                    ->where('ia.status', 'confirmada')
                    ->where('p.presente', true)
                    ->where('a.duracao_minutos', '>', 0);
            })
            ->select('i.id', 'e.nome as edicao')
            ->orderByDesc('i.id')
            ->get();

        return view('certificados.historico', compact(
            'certificados',
            'inscricoesDisponiveis'
        ));
    }

    // Emite um certificado para uma inscricao do usuario conectado
    public function emitir(
        Request $request,
        EmissorCertificados $emissor,
        int $inscricao
    ): RedirectResponse {

        // Verifica se a inscricao pertence ao usuario autenticado.
        $pertenceAoUsuario = DB::table('inscricoes')
            ->where('id', $inscricao)
            ->where('user_id', $request->user()->id)
            ->exists();

        if (!$pertenceAoUsuario) {
            abort(404, 'Inscricao nao encontrada.');
        }

        try {
            // O servico verifica status, presencas, horas e duplicidade.
            $emissor->emitir($inscricao);

            return redirect()
                ->route('certificados.historico')
                ->with(
                    'sucesso',
                    'Certificado emitido com sucesso!'
                );

        } catch (RuntimeException $erro) {

            return redirect()
                ->route('certificados.historico')
                ->with('erro', $erro->getMessage());
        }
    }

    // Download de certificado pertencente ao usuario autenticado
    public function baixar(Request $request, int $certificado)
    {
        $registro = DB::table('certificados as c')
            ->join('inscricoes as i', 'i.id', '=', 'c.inscricao_id')
            ->join('users as u', 'u.id', '=', 'i.user_id')
            ->where('c.id', $certificado)
            ->where('i.user_id', $request->user()->id)
            ->where('c.status', 'emitido')
            ->select(
                'c.id',
                'c.codigo_validacao',
                'c.carga_horaria_minutos',
                'u.name as participante'
            )
            ->first();

        if (!$registro) {
            abort(404, 'Certificado nao encontrado.');
        }

        $dados = [
            'participante' => $registro->participante,
            'totalMinutos' => (int) $registro->carga_horaria_minutos,
            'codigoValidacao' => $registro->codigo_validacao,
            'urlValidacao' => route(
                'certificados.validar',
                ['codigo' => $registro->codigo_validacao]
            ),
        ];

        return Pdf::loadView('certificados.pdf', $dados)
            ->setPaper('a4', 'landscape')
            ->download('certificado-' . $registro->id . '.pdf');
    }
}
