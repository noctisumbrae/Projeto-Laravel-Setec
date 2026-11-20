<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Inscricao extends Model
{
    protected $table = 'inscricoes';
    protected $fillable = [
        'user_id',
        'edicao_id',
        'status',
    ];
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
        return $this->belongsToMany(
            Atividade::class,
            'inscricao_atividade',
            'inscricao_id',
            'atividade_id'
        )
        ->withPivot('status')
        ->withTimestamps();
    }
}
