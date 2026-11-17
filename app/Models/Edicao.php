<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * MODEL PROVISÓRIO - pertence à EQUIPE 1 (Administração).
 * A Equipe 2 só LÊ esta tabela. Os métodos de período de inscrição ficam aqui
 * porque são regras da própria edição; a Equipe 1 pode mantê-los ao assumir o model.
 */
class Edicao extends Model
{
    protected $table = 'edicoes';

    protected $fillable = [
        'nome', 'ano', 'descricao', 'data_inicio', 'data_fim', 'ativa', 'valor_inscricao',
        'inscricao_inicio_fatec', 'inscricao_fim_fatec',
        'inscricoes_externas_habilitadas', 'inscricao_inicio_externo', 'inscricao_fim_externo',
        'limite_palestras', 'limite_minicursos',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
        'ativa' => 'boolean',
        'valor_inscricao' => 'decimal:2',
        'inscricao_inicio_fatec' => 'date',
        'inscricao_fim_fatec' => 'date',
        'inscricoes_externas_habilitadas' => 'boolean',
        'inscricao_inicio_externo' => 'date',
        'inscricao_fim_externo' => 'date',
    ];

    // ---------- Relacionamentos ----------

    public function atividades(): HasMany
    {
        return $this->hasMany(Atividade::class);
    }

    public function inscricoes(): HasMany
    {
        return $this->hasMany(Inscricao::class);
    }

    // ---------- Consultas ----------

    /** Edição vigente = a edição ativa mais recente. */
    public static function vigente(): ?self
    {
        return static::where('ativa', true)->orderByDesc('ano')->first();
    }

    // ---------- Regras de período de inscrição ----------

    /** @return array{0: ?\Illuminate\Support\Carbon, 1: ?\Illuminate\Support\Carbon} */
    public function periodoPara(string $tipoParticipante): array
    {
        if ($tipoParticipante === User::TIPO_ALUNO) {
            return [$this->inscricao_inicio_fatec, $this->inscricao_fim_fatec];
        }

        if (! $this->inscricoes_externas_habilitadas) {
            return [null, null];
        }

        return [$this->inscricao_inicio_externo, $this->inscricao_fim_externo];
    }

    public function inscricoesAbertasPara(string $tipoParticipante): bool
    {
        [$inicio, $fim] = $this->periodoPara($tipoParticipante);

        if (! $inicio || ! $fim) {
            return false;
        }

        return today()->between($inicio, $fim);
    }

    /** Mensagem explicando por que as inscrições estão fechadas (null se estiverem abertas). */
    public function motivoInscricaoFechada(string $tipoParticipante): ?string
    {
        if ($this->inscricoesAbertasPara($tipoParticipante)) {
            return null;
        }

        [$inicio, $fim] = $this->periodoPara($tipoParticipante);

        if ($tipoParticipante === User::TIPO_EXTERNO && ! $this->inscricoes_externas_habilitadas) {
            return 'As inscrições para o público externo não estão habilitadas nesta edição.';
        }

        if (! $inicio || ! $fim) {
            return 'O período de inscrição ainda não foi definido.';
        }

        return today()->lt($inicio)
            ? 'As inscrições abrem em ' . $inicio->format('d/m/Y') . '.'
            : 'O período de inscrição terminou em ' . $fim->format('d/m/Y') . '.';
    }

    /** Texto do período para exibição (ex.: "01/11/2026 a 20/11/2026"). */
    public function descricaoPeriodo(string $tipoParticipante): string
    {
        [$inicio, $fim] = $this->periodoPara($tipoParticipante);

        if (! $inicio || ! $fim) {
            return $tipoParticipante === User::TIPO_EXTERNO && ! $this->inscricoes_externas_habilitadas
                ? 'Não habilitadas'
                : 'A definir';
        }

        return $inicio->format('d/m/Y') . ' a ' . $fim->format('d/m/Y');
    }
}
