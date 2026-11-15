<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'edition_id', 
        'title', 
        'starts_at', 
        'ends_at', 
        'location',
        'qr_token', 
        'attendance_window_start', 
        'attendance_window_end',
    ];

    protected $casts = [
        'starts_at'               => 'datetime',
        'ends_at'                 => 'datetime',
        'attendance_window_start' => 'datetime',
        'attendance_window_end'   => 'datetime',
    ];

    public function hasToken(): bool
    {
        return ! empty($this->qr_token);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function edition(): BelongsTo
    {
        return $this->belongsTo(Edition::class);
    }
}