<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'edition_id', 
        'activity_id', 
        'participant_id',
        'registered_at', 
        'ip_address', 
        'user_agent',
        'is_manual', 
        'corrected_by', 
        'correction_justification',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'is_manual'     => 'boolean',
    ];

    // Relacionamento: Uma presença pertence a um participante
    public function participant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'participant_id');
    }

    // Relacionamento: Quem corrigiu a presença (admin)
    public function correctedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'corrected_by');
    }

    // Relacionamento: Uma presença pertence a uma atividade
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }
}