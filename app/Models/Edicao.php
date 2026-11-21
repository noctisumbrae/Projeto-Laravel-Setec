<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Edicao extends Model
{
    public const PLANEJADA = 'planejada';
    public const ATIVA = 'ativa';
    public const ENCERRADA = 'encerrada';

    public const STATUS = [self::PLANEJADA, self::ATIVA, self::ENCERRADA];

    protected $table = 'edicoes';

    protected $fillable = [
        'nome',
        'ano',
        'data_inicio',
        'data_fim',
        'descricao',
        'status',
        'inscricao_interna_inicio',
        'inscricao_interna_fim',
        'inscricao_externa_inicio',
        'inscricao_externa_fim',
        'permite_inscricao_externa',
        'limite_palestras',
        'limite_minicursos',
        'valor_inscricao',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
        'ano' => 'integer',
        'inscricao_interna_inicio' => 'datetime',
        'inscricao_interna_fim' => 'datetime',
        'inscricao_externa_inicio' => 'datetime',
        'inscricao_externa_fim' => 'datetime',
        'permite_inscricao_externa' => 'boolean',
        'valor_inscricao' => 'decimal:2',
    ];

    public function scopeAtiva(Builder $query): Builder
    {
        return $query->where('status', self::ATIVA);
    }

    public function scopeEncerradas(Builder $query): Builder
    {
        return $query->where('status', self::ENCERRADA);
    }

    public function estaAtiva(): bool
    {
        return $this->status === self::ATIVA;
    }

    public function estaEncerrada(): bool
    {
        return $this->status === self::ENCERRADA;
    }

    /**
     * Útil para as outras equipes: Edicao::vigente()
     */
    public static function vigente(): ?self
    {
        return static::ativa()->first();
    }
}
