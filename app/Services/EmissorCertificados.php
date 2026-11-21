<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class EmissorCertificados
{
    public function __construct(
        private CalculadoraCargaHoraria $calculadora
    ) {
    }

    public function emitir(int $inscricaoId): object
    {
        return DB::transaction(function () use ($inscricaoId) {

            // Localiza a inscricao e bloqueia alteracoes
            // concorrentes durante a emissao.
            $inscricao = DB::table('inscricoes')
                ->where('id', $inscricaoId)
                ->lockForUpdate()
                ->first();

            if (!$inscricao || $inscricao->status !== 'confirmada') {
                throw new RuntimeException(
                    'Inscricao inexistente ou nao confirmada.'
                );
            }

            // Impede a emissao de certificados duplicados.
            $existente = DB::table('certificados')
                ->where('inscricao_id', $inscricaoId)
                ->first();

            if ($existente) {
                if ($existente->status !== 'emitido') {
                    throw new RuntimeException(
                        'Esta inscricao possui um certificado que nao esta valido.'
                    );
                }

                return $existente;
            }

            // Consulta as atividades e presencas registradas.
            $atividades = DB::table('inscricao_atividade as ia')
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
                ->where('ia.inscricao_id', $inscricaoId)
                ->where('ia.status', 'confirmada')
                ->select('a.duracao_minutos', 'p.presente')
                ->get()
                ->map(function ($atividade) {
                    return [
                        'duracao_minutos' =>
                            (int) $atividade->duracao_minutos,

                        'presenca_confirmada' =>
                            (bool) $atividade->presente,
                    ];
                })
                ->all();

            // Reutiliza a calculadora criada anteriormente.
            $totalMinutos = $this->calculadora->calcular(
                $atividades
            );

            if ($totalMinutos <= 0) {
                throw new RuntimeException(
                    'Nao existem horas validas para emitir o certificado.'
                );
            }

            // Registra o certificado e seu codigo unico.
            $id = DB::table('certificados')->insertGetId([
                'inscricao_id' => $inscricaoId,
                'codigo_validacao' => (string) Str::uuid(),
                'carga_horaria_minutos' => $totalMinutos,
                'emitido_em' => now(),
                'status' => 'emitido',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return DB::table('certificados')
                ->where('id', $id)
                ->first();
        });
    }
}
