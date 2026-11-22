<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\CertificadosTesteSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class EmissaoCertificadoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();

        // Prepara dados ficticios no banco de testes.
        $this->seed(CertificadosTesteSeeder::class);
    }

    private function dadosDaAna(): array
    {
        $ana = User::where(
            'email',
            'ana.certificados@example.test'
        )->firstOrFail();

        $inscricaoId = DB::table('inscricoes')
            ->where('user_id', $ana->id)
            ->value('id');

        return [$ana, (int) $inscricaoId];
    }

    public function test_participante_emite_seu_certificado(): void
    {
        [$ana, $inscricaoId] = $this->dadosDaAna();

        $this->actingAs($ana)
            ->post(route(
                'certificados.historico.emitir',
                ['inscricao' => $inscricaoId]
            ))
            ->assertRedirect(route('certificados.historico'))
            ->assertSessionHas('sucesso');

        $this->assertDatabaseHas('certificados', [
            'inscricao_id' => $inscricaoId,
            'carga_horaria_minutos' => 180,
            'status' => 'emitido',
        ]);

        $codigo = DB::table('certificados')
            ->where('inscricao_id', $inscricaoId)
            ->value('codigo_validacao');

        $this->assertTrue(Str::isUuid($codigo));
    }

    public function test_participante_nao_emite_certificado_de_outra_pessoa(): void
    {
        [$ana, $inscricaoId] = $this->dadosDaAna();

        $outraPessoa = User::factory()->create();

        $this->actingAs($outraPessoa)
            ->post(route(
                'certificados.historico.emitir',
                ['inscricao' => $inscricaoId]
            ))
            ->assertNotFound();

        $this->assertDatabaseCount('certificados', 0);
    }

    public function test_nao_emite_certificado_sem_horas_validas(): void
    {
        [$ana, $inscricaoId] = $this->dadosDaAna();

        // Simula ausencia em todas as atividades.
        DB::table('presencas')
            ->update(['presente' => false]);

        $this->actingAs($ana)
            ->post(route(
                'certificados.historico.emitir',
                ['inscricao' => $inscricaoId]
            ))
            ->assertRedirect(route('certificados.historico'))
            ->assertSessionHas('erro');

        $this->assertDatabaseCount('certificados', 0);
    }

    public function test_nao_cria_certificados_duplicados(): void
    {
        [$ana, $inscricaoId] = $this->dadosDaAna();

        $url = route(
            'certificados.historico.emitir',
            ['inscricao' => $inscricaoId]
        );

        // Primeira solicitacao.
        $this->actingAs($ana)
            ->post($url)
            ->assertRedirect(route('certificados.historico'));

        $codigoOriginal = DB::table('certificados')
            ->where('inscricao_id', $inscricaoId)
            ->value('codigo_validacao');

        // Segunda solicitacao para a mesma inscricao.
        $this->actingAs($ana)
            ->post($url)
            ->assertRedirect(route('certificados.historico'));

        $codigoFinal = DB::table('certificados')
            ->where('inscricao_id', $inscricaoId)
            ->value('codigo_validacao');

        $this->assertDatabaseCount('certificados', 1);

        $this->assertSame(
            $codigoOriginal,
            $codigoFinal
        );
    }
}
