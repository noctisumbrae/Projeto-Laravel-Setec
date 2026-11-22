<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Atividade extends Model
{
    protected $table = 'atividades';

    protected $fillable = [
        'titulo',
        'descricao',
        'tipo',
        'responsavel',
        'data',
        'horario',
        'duracao',
        'capacidade',
        'local_id',
        'edicao_id'
    ];

    protected $casts = [
        'data' => 'date',
        'duracao' => 'integer',
        'capacidade' => 'integer',
    ];

    public function local(): BelongsTo
    {
        return $this->belongsTo(Local::class);
    }

    public function edicao(): BelongsTo
    {
    return $this->belongsTo(Edicao::class);
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
