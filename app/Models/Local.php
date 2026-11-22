<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function atividades(): HasMany
    {
        return $this->hasMany(Atividade::class);
    }
}
