<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Local extends Model
{
    protected $table = 'locais';
    protected $fillable = [
        'nome',
        'capacidade',
    ];
    public function atividades(): HasMany
    {
        return $this->hasMany(Atividade::class);
    }
}
