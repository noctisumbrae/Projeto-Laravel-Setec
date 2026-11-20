<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Atividade extends Model
{
    protected $table = 'atividades';
    protected $fillable = [
        'edicao_id',
        'local_id',
        'titulo',
        'descricao',
        'tipo',
        'responsavel',
        'inicio',
        'duracao_minutos',
        'capacidade',
    ];
    protected $casts = [
        'inicio' => 'datetime',
        'duracao_minutos' => 'integer',
        'capacidade' => 'integer',
    ];
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
    return $this->belongsToMany(
        Inscricao::class,
        'inscricao_atividade',
        'atividade_id',
        'inscricao_id'
    )
    ->withPivot('status')
    ->withTimestamps();
}

}
