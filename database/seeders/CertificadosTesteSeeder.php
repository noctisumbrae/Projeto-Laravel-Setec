<?php

namespace Database\Seeders;

use App\Models\Atividade;
use App\Models\Edicao;
use App\Models\Inscricao;
use App\Models\Local;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CertificadosTesteSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            // Participante ficticia
            $usuario = User::firstOrCreate(
                ['email' => 'ana.certificados@example.test'],
                [
                    'name' => 'Ana Souza',
                    'password' => Hash::make(Str::random(40)),
                    'tipo_participante' => 'externo',
                    'perfil' => 'participante',
                ]
            );

            // Edicao do evento
            $edicao = Edicao::firstOrCreate(
                ['ano' => 2026],
                [
                    'nome' => 'Semana de Tecnologia 2026',
                    'data_inicio' => '2026-11-18',
                    'data_fim' => '2026-11-20',
                    'status' => 'encerrada',
                ]
            );

            // Local das atividades
            $local = Local::firstOrCreate(
                ['nome' => 'Sala de Testes - Equipe 5'],
                ['capacidade' => 40]
            );

            // Inscricao confirmada da participante
            $inscricao = Inscricao::firstOrCreate(
                [
                    'user_id' => $usuario->id,
                    'edicao_id' => $edicao->id,
                ],
                ['status' => 'confirmada']
            );

            // Atividades e registros de presenca
            $atividades = [
                [
                    'titulo' => 'Palestra de abertura',
                    'tipo' => 'palestra',
                    'inicio' => '2026-11-18 09:00:00',
                    'duracao' => 60,
                    'presente' => true,
                ],
                [
                    'titulo' => 'Minicurso de Laravel',
                    'tipo' => 'minicurso',
                    'inicio' => '2026-11-18 10:30:00',
                    'duracao' => 120,
                    'presente' => true,
                ],
                [
                    'titulo' => 'Oficina de Redes',
                    'tipo' => 'oficina',
                    'inicio' => '2026-11-19 14:00:00',
                    'duracao' => 90,
                    'presente' => false,
                ],
            ];

            foreach ($atividades as $dados) {

                $atividade = Atividade::firstOrCreate(
                    [
                        'edicao_id' => $edicao->id,
                        'titulo' => $dados['titulo'],
                    ],
                    [
                        'local_id' => $local->id,
                        'tipo' => $dados['tipo'],
                        'responsavel' => 'Equipe 5',
                        'inicio' => $dados['inicio'],
                        'duracao_minutos' => $dados['duracao'],
                        'capacidade' => 40,
                    ]
                );

                // Vincula a atividade a inscricao
                DB::table('inscricao_atividade')->updateOrInsert(
                    [
                        'inscricao_id' => $inscricao->id,
                        'atividade_id' => $atividade->id,
                    ],
                    [
                        'status' => 'confirmada',
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );

                $vinculoId = DB::table('inscricao_atividade')
                    ->where('inscricao_id', $inscricao->id)
                    ->where('atividade_id', $atividade->id)
                    ->value('id');

                // Registra se a participante compareceu
                DB::table('presencas')->updateOrInsert(
                    ['inscricao_atividade_id' => $vinculoId],
                    [
                        'presente' => $dados['presente'],
                        'registrada_em' => now(),
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        });
    }
}
