<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Carbon\Carbon;

class ValidacaoCertificadoController extends Controller
{
    public function index(Request $request): View
    {
        $certificado = null;
        $mensagem = null;

        if ($request->query->has('codigo')) {

            $validacao = Validator::make(
                $request->query(),
                [
                    'codigo' => ['required', 'string', 'uuid'],
                ]
            );

            if ($validacao->fails()) {
                $mensagem = 'Informe um código de validação válido.';
            } else {

                $codigo = $validacao->validated()['codigo'];

                $certificado = DB::table('certificados as c')
                    ->join('inscricoes as i', 'i.id', '=', 'c.inscricao_id')
                    ->join('users as u', 'u.id', '=', 'i.user_id')
                    ->join('edicoes as e', 'e.id', '=', 'i.edicao_id')
                    ->where('c.codigo_validacao', $codigo)
                    ->select(
                        'u.name as participante',
                        'e.nome as edicao',
                        'c.carga_horaria_minutos',
                        'c.emitido_em',
                        'c.status'
                    )
                    ->first();

                if (!$certificado) {
                    $mensagem = 'Certificado não encontrado.';
                } elseif ($certificado->status !== 'emitido') {
                    $mensagem = 'Este certificado não está válido.';
                    $certificado = null;
                } else {
                    $minutos = (int) $certificado->carga_horaria_minutos;

                    $certificado->carga_formatada = sprintf(
                        '%dh %02dmin',
                        intdiv($minutos, 60),
                        $minutos % 60
                    );

                    $certificado->data_formatada = Carbon::parse(
                        $certificado->emitido_em
                    )->format('d/m/Y');
                }
            }
        }

        return view('certificados.validar', [
            'certificado' => $certificado,
            'mensagem' => $mensagem,
        ]);
    }
}
