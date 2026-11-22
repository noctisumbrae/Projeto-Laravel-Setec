<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\CertificadosTesteSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Tests\TestCase;

class FluxoCertificadoTest extends TestCase
{
    use RefreshDatabase;

    public function test_fluxo_completo_com_diferentes_participantes(): void
    {
        $this->withoutVite();

        $this->seed(CertificadosTesteSeeder::class);

        $ana = User::where(
            'email',
            'ana.certificados@example.test'
        )->firstOrFail();

        $inscricaoAna = DB::table('inscricoes')
            ->where('user_id', $ana->id)
            ->value('id');

        // Cria outro participante.
        $pedro = User::factory()->create([
            'name' => 'Pedro Santos',
            'tipo_participante' => 'externo',
            'perfil' => 'participante',
        ]);

        // Cria uma edicao diferente do evento.
        $edicaoId = DB::table('edicoes')->insertGetId([
            'nome' => 'Semana de Tecnologia 2025',
            'ano' => 2025,
            'data_inicio' => '2025-11-18',
            'data_fim' => '2025-11-20',
            'status' => 'encerrada',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $localId = DB::table('locais')->value('id');

        // Cria uma atividade com duracao de 75 minutos.
        $atividadeId = DB::table('atividades')->insertGetId([
            'edicao_id' => $edicaoId,
            'local_id' => $localId,
            'titulo' => 'Palestra de Banco de Dados',
            'tipo' => 'palestra',
            'responsavel' => 'Equipe 5',
            'inicio' => '2025-11-18 09:00:00',
            'duracao_minutos' => 75,
            'capacidade' => 40,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Inscricao do segundo participante.
        $inscricaoPedro = DB::table('inscricoes')->insertGetId([
            'user_id' => $pedro->id,
            'edicao_id' => $edicaoId,
            'status' => 'confirmada',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $vinculoId = DB::table('inscricao_atividade')->insertGetId([
            'inscricao_id' => $inscricaoPedro,
            'atividade_id' => $atividadeId,
            'status' => 'confirmada',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('presencas')->insert([
            'inscricao_atividade_id' => $vinculoId,
            'presente' => true,
            'registrada_em' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Emissao do certificado da Ana.
        $this->actingAs($ana)
            ->post(route('certificados.historico.emitir', $inscricaoAna))
            ->assertRedirect(route('certificados.historico'))
            ->assertSessionHas('sucesso');

        // Verifica se Pedro possui uma emissao disponivel.
        $this->actingAs($pedro)
            ->get(route('certificados.historico'))
            ->assertOk()
            ->assertSee('Semana de Tecnologia 2025')
            ->assertSee('Emitir certificado')
            ->assertDontSee('Semana de Tecnologia 2026');

        // Emissao do certificado do Pedro.
        $this->actingAs($pedro)
            ->post(route('certificados.historico.emitir', $inscricaoPedro))
            ->assertRedirect(route('certificados.historico'))
            ->assertSessionHas('sucesso');

        $certificadoAna = DB::table('certificados')
            ->where('inscricao_id', $inscricaoAna)
            ->first();

        $certificadoPedro = DB::table('certificados')
            ->where('inscricao_id', $inscricaoPedro)
            ->first();

        // Confere as cargas horarias individuais.
        $this->assertEquals(
            180,
            $certificadoAna->carga_horaria_minutos
        );

        $this->assertEquals(
            75,
            $certificadoPedro->carga_horaria_minutos
        );

        $this->assertNotSame(
            $certificadoAna->codigo_validacao,
            $certificadoPedro->codigo_validacao
        );

        $this->assertDatabaseCount('certificados', 2);

        // Impede Pedro de baixar o certificado de Ana.
        $this->actingAs($pedro)
            ->get(route(
                'certificados.historico.pdf',
                $certificadoAna->id
            ))
            ->assertNotFound();

        // Confere os dados enviados ao modelo do PDF.
        $dadosPdf = null;

        View::composer('certificados.pdf', function ($view) use (&$dadosPdf) {
            $dadosPdf = $view->getData();
        });

        // Download do certificado correto.
        $this->actingAs($pedro)
            ->get(route(
                'certificados.historico.pdf',
                $certificadoPedro->id
            ))
            ->assertOk()
            ->assertDownload(
                'certificado-' . $certificadoPedro->id . '.pdf'
            );

        $this->assertNotNull($dadosPdf);
        $this->assertSame('Pedro Santos', $dadosPdf['participante']);
        $this->assertSame('Semana de Tecnologia 2025', $dadosPdf['edicao']);
        $this->assertSame(75, $dadosPdf['totalMinutos']);

        $this->assertSame(
            $certificadoPedro->codigo_validacao,
            $dadosPdf['codigoValidacao']
        );

        // Testa a validacao desenvolvida pela Livia.
        $this->get(route('certificados.validar', [
            'codigo' => $certificadoPedro->codigo_validacao,
        ]))
            ->assertOk()
            ->assertSee('Pedro Santos')
            ->assertSee('Semana de Tecnologia 2025')
            ->assertSee('1h 15min');
    }
}
