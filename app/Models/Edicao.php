<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Edicao extends Model
{
    public const PLANEJADA = 'planejada';
    public const ATIVA = 'ativa';
    public const ENCERRADA = 'encerrada';
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
    public static function vigente(): ?self
    {
        return static::ativa()->first();
    }
}
