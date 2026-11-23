<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * MODEL - Inscrição de um participante em uma edição (EQUIPE 2).
 */
class Inscricao extends Model
{
    public const PENDENTE   = 'pendente';
    public const CONFIRMADA = 'confirmada';
    public const CANCELADA  = 'cancelada';

    public const ATIVIDADE_SELECIONADA = 'selecionada';
    public const ATIVIDADE_CONFIRMADA  = 'confirmada';
    public const ATIVIDADE_LOTADA      = 'lotada';

    protected $table = 'inscricoes';

    protected $fillable = ['user_id', 'edicao_id', 'status'];

    // ---------- Relacionamentos ----------

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function edicao(): BelongsTo
    {
        return $this->belongsTo(Edicao::class);
    }

    public function atividades(): BelongsToMany
    {
        return $this->belongsToMany(Atividade::class, 'inscricao_atividade')
            ->withPivot('status')
            ->withTimestamps();
    }

    // ---------- Regras ----------

    /** A seleção de atividades só pode ser alterada enquanto a inscrição está pendente. */
    public function editavel(): bool
    {
        return $this->status === self::PENDENTE;
    }

    // ---------- Apoio às views ----------

    public function getRotuloStatusAttribute(): string
    {
        return match ($this->status) {
            self::CONFIRMADA => 'Confirmada',
            self::CANCELADA  => 'Cancelada',
            default          => 'Aguardando pagamento',
        };
    }

    public function getClasseStatusAttribute(): string
    {
        return match ($this->status) {
            self::CONFIRMADA => 'selo--verde',
            self::CANCELADA  => 'selo--cinza',
            default          => 'selo--amarelo',
        };
    }

    /** @return array{0: string, 1: string} [rótulo, classe CSS] */
    public static function apresentacaoStatusAtividade(string $status): array
    {
        return match ($status) {
            self::ATIVIDADE_CONFIRMADA => ['Vaga confirmada', 'selo--verde'],
            self::ATIVIDADE_LOTADA     => ['Lotou: escolha outra', 'selo--vermelho'],
            default                    => ['Selecionada (sem vaga garantida)', 'selo--amarelo'],
        };
    }
}
