<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const TIPO_ALUNO = 'aluno_fatec';
    public const TIPO_EXTERNO = 'externo';
    public const PERFIL_ADMIN = 'admin';
    public const PERFIL_PARTICIPANTE = 'participante';

    protected $fillable = [
        'name', 'email', 'password', 'cpf', 'telefone', 'ra',
        'tipo_participante', 'perfil',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function inscricoes(): HasMany
    {
        return $this->hasMany(Inscricao::class);
    }

    public function ehAluno(): bool
    {
        return $this->tipo_participante === self::TIPO_ALUNO;
    }

    public function ehAdmin(): bool
    {
        return $this->perfil === self::PERFIL_ADMIN;
    }

    public function getRotuloTipoAttribute(): string
    {
        return $this->ehAluno() ? 'Aluno da Fatec' : 'Público externo';
    }

    public function getCpfFormatadoAttribute(): string
    {
        $cpf = (string) $this->cpf;
        return strlen($cpf) === 11
            ? substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2)
            : $cpf;
    }

    public function getTelefoneFormatadoAttribute(): string
    {
        $telefone = (string) $this->telefone;
        return match (strlen($telefone)) {
            11 => '(' . substr($telefone, 0, 2) . ') ' . substr($telefone, 2, 5) . '-' . substr($telefone, 7),
            10 => '(' . substr($telefone, 0, 2) . ') ' . substr($telefone, 2, 4) . '-' . substr($telefone, 6),
            default => $telefone,
        };
    }
}
