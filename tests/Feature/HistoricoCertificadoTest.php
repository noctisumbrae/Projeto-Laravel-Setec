<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class HistoricoCertificadoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Evita depender dos arquivos do Vite nos testes.
        $this->withoutVite();
    }

    // Cria uma edicao, inscricao e certificado para teste.
    private function criarCertificado(
        User $usuario,
        int $ano,
        string $nomeEdicao
    ): int {
        $edicaoId = DB::table('edicoes')->insertGetId([
            'nome' => $nomeEdicao,
            'ano' => $ano,
            'data_inicio' => "$ano-11-18",
            'data_fim' => "$ano-11-20",
            'status' => 'encerrada',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $inscricaoId = DB::table('inscricoes')->insertGetId([
            'user_id' => $usuario->id,
            'edicao_id' => $edicaoId,
            'status' => 'confirmada',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return DB::table('certificados')->insertGetId([
            'inscricao_id' => $inscricaoId,
            'codigo_validacao' => (string) Str::uuid(),
            'carga_horaria_minutos' => 180,
            'emitido_em' => now(),
            'status' => 'emitido',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_visitante_nao_acessa_o_historico(): void
    {
        $this->withHeader('Accept', 'application/json')
            ->get('/meus-certificados')
            ->assertUnauthorized();
    }

    public function test_participante_ve_apenas_seus_certificados(): void
    {
        $ana = User::factory()->create();
        $outraPessoa = User::factory()->create();

        $this->criarCertificado(
            $ana,
            2026,
            'Semana de Tecnologia 2026'
        );

        $this->criarCertificado(
            $outraPessoa,
            2027,
            'Semana de Tecnologia 2027'
        );

        $this->actingAs($ana)
            ->get('/meus-certificados')
            ->assertOk()
            ->assertSee('Semana de Tecnologia 2026')
            ->assertDontSee('Semana de Tecnologia 2027');
    }

    public function test_participante_nao_baixa_certificado_de_outra_pessoa(): void
    {
        $ana = User::factory()->create();
        $outraPessoa = User::factory()->create();

        $certificadoId = $this->criarCertificado(
            $outraPessoa,
            2026,
            'Semana de Tecnologia 2026'
        );

        $this->actingAs($ana)
            ->get("/meus-certificados/{$certificadoId}/pdf")
            ->assertNotFound();
    }

    public function test_participante_baixa_seu_proprio_certificado(): void
    {
        $ana = User::factory()->create();

        $certificadoId = $this->criarCertificado(
            $ana,
            2026,
            'Semana de Tecnologia 2026'
        );

        $resposta = $this->actingAs($ana)
            ->get("/meus-certificados/{$certificadoId}/pdf");

        $resposta->assertOk();

        $this->assertStringContainsString(
            'application/pdf',
            $resposta->headers->get('Content-Type')
        );
    }
}
