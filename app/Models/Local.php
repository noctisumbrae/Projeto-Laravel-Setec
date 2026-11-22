<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Local extends Model
{
    protected $table = 'locals';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'nome',
        'tipo',
        'capacidade',
    ];

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
}