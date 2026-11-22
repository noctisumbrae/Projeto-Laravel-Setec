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
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
        'ano' => 'integer',
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

    public function inscricoes()
    {
        return $this->hasMany(Inscricao::class);
    }

    public function atividades()
    {
        return $this->hasMany(Atividade::class);
    }

    public function locais()
    {
        return $this->hasMany(Local::class);
    }
}