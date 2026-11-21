<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}