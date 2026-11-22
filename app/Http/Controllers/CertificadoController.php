<?php

namespace App\Http\Controllers;

use App\Services\CalculadoraCargaHoraria;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class CertificadoController extends Controller
{
    // Consulta os dados da participante no banco
    private function dadosDoBanco(CalculadoraCargaHoraria $calculadora): array
    {
        $inscricao = DB::table('inscricoes as i')
            ->join('users as u', 'u.id', '=', 'i.user_id')
            ->where('u.email', 'ana.certificados@example.test')
            ->where('i.status', 'confirmada')
            ->select('i.id', 'u.name')
            ->first();

        if ($inscricao === null) {
            abort(404, 'Inscricao de teste nao encontrada.');
        }

        $participante = $inscricao->name;

        // Busca as atividades e presencas registradas
        $atividades = DB::table('inscricao_atividade as ia')
            ->join('atividades as a', 'a.id', '=', 'ia.atividade_id')
            ->leftJoin(
                'presencas as p',
                'p.inscricao_atividade_id',
                '=',
                'ia.id'
            )
            ->where('ia.inscricao_id', $inscricao->id)
            ->where('ia.status', 'confirmada')
            ->orderBy('a.inicio')
            ->get([
                'a.titulo',
                'a.duracao_minutos',
                'p.presente'
            ])
            ->map(function ($atividade) {
                return [
                    'titulo' => $atividade->titulo,
                    'duracao_minutos' => (int) $atividade->duracao_minutos,
                    'presenca_confirmada' => (bool) $atividade->presente
                ];
            })
            ->all();

        // Calcula apenas as atividades com presenca confirmada
        $totalMinutos = $calculadora->calcular($atividades);

        return compact(
            'participante',
            'atividades',
            'totalMinutos'
        );
    }

    // Pagina com a lista de atividades
    public function index(CalculadoraCargaHoraria $calculadora)
    {
        return view(
            'certificados.index',
            $this->dadosDoBanco($calculadora)
        );
    }

    // Pagina com o modelo visual do certificado
    public function modelo(CalculadoraCargaHoraria $calculadora)
    {
        return view(
            'certificados.modelo',
            $this->dadosDoBanco($calculadora)
        );
    }

    // Gera o PDF com o codigo de validacao
    public function baixarPdf(CalculadoraCargaHoraria $calculadora)
    {
        $dados = $this->dadosDoBanco($calculadora);

        // Consulta o certificado ja emitido no banco
        $certificado = DB::table('certificados as c')
            ->join('inscricoes as i', 'i.id', '=', 'c.inscricao_id')
            ->join('users as u', 'u.id', '=', 'i.user_id')
            ->where('u.email', 'ana.certificados@example.test')
            ->where('c.status', 'emitido')
            ->select('c.codigo_validacao')
            ->first();

        if (!$certificado) {
            abort(404, 'Certificado ainda nao emitido.');
        }

        // Adiciona os dados de validacao ao PDF
        $dados['codigoValidacao'] = $certificado->codigo_validacao;

        $dados['urlValidacao'] = route(
            'certificados.validar',
            ['codigo' => $certificado->codigo_validacao]
        );

        return Pdf::loadView('certificados.pdf', $dados)
            ->setPaper('a4', 'landscape')
            ->download('certificado-ana-souza-teste.pdf');
    }
}
