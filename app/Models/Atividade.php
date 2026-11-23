<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * MODEL PROVISÓRIO - pertence à EQUIPE 1 (palestras e minicursos).
 * Os métodos de vagas e de conflito de horário são usados pela Equipe 2.
 */
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

    // ---------- Escopos ----------

    /** Carrega a quantidade de vagas já CONFIRMADAS em uma única consulta (evita N+1). */
    public function scopeComOcupacao(Builder $query): Builder
    {
        return $query->withCount([
            'inscricoes as ocupadas' => fn ($q) => $q->where('inscricao_atividade.status', Inscricao::ATIVIDADE_CONFIRMADA),
        ]);
    }

    // ---------- Acessores ----------

    public function getFimAttribute(): Carbon
    {
        return $this->inicio->copy()->addMinutes($this->duracao_minutos);
    }

    /** Vagas = capacidade - inscrições confirmadas (selecionar não ocupa vaga). */
    public function getVagasDisponiveisAttribute(): int
    {
        $ocupadas = $this->ocupadas
            ?? $this->inscricoes()->wherePivot('status', Inscricao::ATIVIDADE_CONFIRMADA)->count();

        return max(0, $this->capacidade - $ocupadas);
    }

    public function getRotuloTipoAttribute(): string
    {
        return $this->tipo === self::TIPO_MINICURSO ? 'Minicurso' : 'Palestra';
    }

    // ---------- Regras de horário ----------

    /** Duas atividades conflitam quando os intervalos se sobrepõem. */
    public function conflitaCom(Atividade $outra): bool
    {
        return $this->inicio->lt($outra->fim) && $this->fim->gt($outra->inicio);
    }

    /**
     * Procura pares de atividades com horários sobrepostos.
     *
     * @param  Collection<int, Atividade>  $atividades
     * @return array<int, array{0: Atividade, 1: Atividade}>
     */
    public static function encontrarConflitos(Collection $atividades): array
    {
        $lista = $atividades->sortBy('inicio')->values();
        $conflitos = [];

        for ($i = 0; $i < $lista->count(); $i++) {
            for ($j = $i + 1; $j < $lista->count(); $j++) {
                if ($lista[$i]->conflitaCom($lista[$j])) {
                    $conflitos[] = [$lista[$i], $lista[$j]];
                }
            }
        }

        return $conflitos;
    }
}
