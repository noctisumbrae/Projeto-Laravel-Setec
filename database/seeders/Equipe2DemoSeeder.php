<?php

namespace Database\Seeders;

use App\Models\Atividade;
use App\Models\Edicao;
use App\Models\Inscricao;
use App\Models\Local;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * SEEDER DE DEMONSTRAÇÃO - dados provisórios para a Equipe 2 testar o módulo
 * enquanto a Equipe 1 não entrega o cadastro de edições e atividades.
 *
 * Uso: php artisan db:seed --class=Equipe2DemoSeeder
 * Senha de todos os usuários de teste: senha1234
 */
class Equipe2DemoSeeder extends Seeder
{
    public function run(): void
    {
        $edicao = Edicao::updateOrCreate(['ano' => 2026], [
            'nome'            => 'Semana de Tecnologia Fatec Presidente Prudente 2026',
            'descricao'       => 'Palestras e minicursos sobre desenvolvimento, dados, inteligência artificial e carreira em TI.',
            'data_inicio'     => today()->addDays(30),
            'data_fim'        => today()->addDays(32),
            'ativa'           => true,
            'valor_inscricao' => 30.00,
            'inscricao_inicio_fatec' => today()->subDays(3),
            'inscricao_fim_fatec'    => today()->addDays(20),
            // Para testar o bloqueio do público externo, mude o início para uma data futura.
            'inscricoes_externas_habilitadas' => true,
            'inscricao_inicio_externo' => today()->subDay(),
            'inscricao_fim_externo'    => today()->addDays(25),
            'limite_palestras'  => 3,
            'limite_minicursos' => 2,
        ]);

        $auditorio = Local::firstOrCreate(['nome' => 'Auditório'], ['capacidade' => 150]);
        $lab1      = Local::firstOrCreate(['nome' => 'Laboratório 1'], ['capacidade' => 30]);
        $lab2      = Local::firstOrCreate(['nome' => 'Laboratório 2'], ['capacidade' => 30]);

        $dia1 = today()->addDays(30);
        $dia2 = today()->addDays(31);

        $dados = [
            ['Abertura e panorama do mercado de TI', 'palestra',  'Profa. Ana Souza',   $dia1->copy()->setTime(19, 0),  60,  $auditorio, 150],
            ['Inteligência artificial na prática',   'palestra',  'Dr. Carlos Lima',    $dia1->copy()->setTime(20, 15), 60,  $auditorio, 150],
            ['Introdução ao Laravel',                'minicurso', 'Prof. Dione Ferrari', $dia1->copy()->setTime(19, 0),  180, $lab1,      30],
            ['Git e GitHub para equipes',            'minicurso', 'Marina Alves',       $dia1->copy()->setTime(20, 0),  120, $lab2,      30], // conflita com o minicurso de Laravel
            ['Carreira em dados',                    'palestra',  'Rafael Costa',       $dia2->copy()->setTime(19, 0),  60,  $auditorio, 150],
            ['Segurança no desenvolvimento web',     'palestra',  'Julia Martins',      $dia2->copy()->setTime(20, 15), 60,  $auditorio, 150],
            ['Mesa-redonda: IA e empregos (vaga única)', 'palestra', 'Convidados',      $dia2->copy()->setTime(21, 30), 45,  $auditorio, 1],   // para testar "lotada"
        ];

        $atividades = [];
        foreach ($dados as [$titulo, $tipo, $responsavel, $inicio, $duracao, $local, $capacidade]) {
            $atividades[$titulo] = Atividade::updateOrCreate(
                ['edicao_id' => $edicao->id, 'titulo' => $titulo],
                [
                    'local_id' => $local->id, 'tipo' => $tipo, 'responsavel' => $responsavel,
                    'inicio' => $inicio, 'duracao_minutos' => $duracao, 'capacidade' => $capacidade,
                    'descricao' => 'Atividade de demonstração da Semana de Tecnologia.',
                ]
            );
        }

        // ---------- Usuários de teste ----------
        User::updateOrCreate(['email' => 'admin@fatec.test'], [
            'name' => 'Administrador SETEC', 'password' => 'senha1234',
            'cpf' => '52998224725', 'telefone' => '18999990000',
            'tipo_participante' => User::TIPO_ALUNO, 'perfil' => User::PERFIL_ADMIN,
        ]);

        User::updateOrCreate(['email' => 'aluno@fatec.test'], [
            'name' => 'Aluno Exemplo', 'password' => 'senha1234',
            'cpf' => '11144477735', 'telefone' => '18988880000', 'ra' => '1234567890',
            'tipo_participante' => User::TIPO_ALUNO, 'perfil' => User::PERFIL_PARTICIPANTE,
        ]);

        User::updateOrCreate(['email' => 'externo@teste.test'], [
            'name' => 'Visitante Externo', 'password' => 'senha1234',
            'cpf' => '16899535009', 'telefone' => '18977770000',
            'tipo_participante' => User::TIPO_EXTERNO, 'perfil' => User::PERFIL_PARTICIPANTE,
        ]);

        // Participante que já ocupou a única vaga da mesa-redonda (simula pagamento aprovado pela Equipe 3).
        $ocupante = User::updateOrCreate(['email' => 'ocupante@teste.test'], [
            'name' => 'Participante Confirmado', 'password' => 'senha1234',
            'cpf' => '39053344705', 'telefone' => '18966660000',
            'tipo_participante' => User::TIPO_EXTERNO, 'perfil' => User::PERFIL_PARTICIPANTE,
        ]);

        $inscricao = Inscricao::firstOrCreate(
            ['user_id' => $ocupante->id, 'edicao_id' => $edicao->id],
            ['status' => Inscricao::CONFIRMADA]
        );

        $inscricao->atividades()->syncWithoutDetaching([
            $atividades['Mesa-redonda: IA e empregos (vaga única)']->id => ['status' => Inscricao::ATIVIDADE_CONFIRMADA],
        ]);
    }
}
