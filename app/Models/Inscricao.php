<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    // ---------- Regras de negócio ----------

    /** A seleção de atividades só pode ser alterada enquanto a inscrição está pendente. */
    public function editavel(): bool
    {
        return $this->status === self::PENDENTE;
    }

    /** Retorna verdadeiro se a inscrição já foi confirmada (pagamento aprovado). */
    public function confirmada(): bool
    {
        return $this->status === self::CONFIRMADA;
    }

    /** Retorna verdadeiro se a inscrição foi cancelada. */
    public function cancelada(): bool
    {
        return $this->status === self::CANCELADA;
    }

    /** Retorna as atividades selecionadas (pivot status = 'selecionada'). */
    public function atividadesSelecionadas(): BelongsToMany
    {
        return $this->atividades()
            ->wherePivot('status', self::ATIVIDADE_SELECIONADA);
    }

    /** Retorna as atividades confirmadas (pivot status = 'confirmada'). */
    public function atividadesConfirmadas(): BelongsToMany
    {
        return $this->atividades()
            ->wherePivot('status', self::ATIVIDADE_CONFIRMADA);
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

    public function getBadgeStatusAttribute(): string
    {
        return match ($this->status) {
            self::CONFIRMADA => 'success',
            self::CANCELADA  => 'danger',
            default          => 'warning',
        };
    }
}
