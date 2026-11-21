<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}