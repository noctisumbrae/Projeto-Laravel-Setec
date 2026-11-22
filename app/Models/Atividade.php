<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class Atividade extends Model
{
    public const TIPO_PALESTRA  = 'palestra';
    public const TIPO_MINICURSO = 'minicurso';

    protected $table = 'atividades';

    protected $fillable = [
        'edicao_id', 'local_id', 'titulo', 'descricao', 'tipo',
        'responsavel', 'inicio', 'duracao_minutos', 'capacidade',
    ];

    protected $casts = [
        'inicio' => 'datetime',
    ];

    // ---------- Relacionamentos ----------

    public function edicao(): BelongsTo
    {
        return $this->belongsTo(Edicao::class);
    }

    public function local(): BelongsTo
    {
        return $this->belongsTo(Local::class);
    }

    public function inscricoes(): BelongsToMany
    {
        return $this->belongsToMany(Inscricao::class, 'inscricao_atividade')
            ->withPivot('status')
            ->withTimestamps();
    }

    // ---------- Acesso conveniente ----------

    public function getTipoLabelAttribute(): string
    {
        return match ($this->tipo) {
            self::TIPO_PALESTRA => 'Palestra',
            self::TIPO_MINICURSO => 'Minicurso',
            default => ucfirst($this->tipo),
        };
    }

    public function getCapacidadeDisponivelAttribute(): int
    {
        $totalInscritos = $this->inscricoes()
            ->wherePivot('status', 'confirmada')
            ->count();

        return max(0, $this->capacidade - $totalInscritos);
    }
}