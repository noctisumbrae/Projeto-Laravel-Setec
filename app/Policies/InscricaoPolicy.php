<?php

namespace App\Policies;

use App\Models\Inscricao;
use App\Models\User;

/**
 * POLICY - o participante só enxerga e altera a própria inscrição.
 * O Laravel descobre esta classe automaticamente (Inscricao -> InscricaoPolicy).
 */
class InscricaoPolicy
{
    public function view(User $user, Inscricao $inscricao): bool
    {
        return $user->id === $inscricao->user_id;
    }

    public function update(User $user, Inscricao $inscricao): bool
    {
        return $this->view($user, $inscricao) && $inscricao->editavel();
    }
}
