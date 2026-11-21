<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HistoricoCertificadoController extends Controller
{
    // Lista os certificados do participante autenticado
    public function index(Request $request)
    {
        $certificados = DB::table('certificados as c')
            ->join('inscricoes as i', 'i.id', '=', 'c.inscricao_id')
            ->join('edicoes as e', 'e.id', '=', 'i.edicao_id')
            ->where('i.user_id', $request->user()->id)
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

        return view('certificados.historico', compact('certificados'));
    }

    // Permite baixar apenas certificados do proprio participante
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