<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Inscricao;
use Illuminate\Auth\Access\HandlesAuthorization;

class InscricaoPolicy
{
    use HandlesAuthorization;

    public function viewAny?(User $user): bool
    {
        return $user->perfil === 'admin' || $user->perfil === 'coordenador';
    }

    public function view?(User $user, Inscricao $inscricao): bool
    {
        return $user->id === $inscricao->user_id ||
               $user->perfil === 'admin' ||
               $user->perfil === 'coordenador' ||
               $inscricao->edicao->usuarioCriador === $user;
    }

    public function create?(User $user): bool
    {
        return $user->perfil !== 'convidado';
    }

    public function update?(User $user, Inscricao $inscricao): bool
    {
        // Só pode editar se a inscrição estiver pendente
        return $inscricao->editavel() && ($user->id === $inscricao->user_id || $user->perfil === 'admin');
    }

    public function delete?(User $user, Inscricao $inscricao): bool
    {
        return $user->id === $inscricao->user_id || $user->perfil === 'admin';
    }

    public function confirmar?(User $user, Inscricao $inscricao): bool
    {
        // Apenas admin ou coordenador podem confirmar inscrições
        return $user->perfil === 'admin' || $user->perfil === 'coordenador';
    }

    public function cancelar?(User $user, Inscricao $inscricao): bool
    {
        return $user->id === $inscricao->user_id || $user->perfil === 'admin';
    }

    public function selecionarAtividade?(User $user, Inscricao $inscricao): bool
    {
        return $inscricao->editavel() && ($user->id === $inscricao->user_id || $user->perfil === 'admin');
    }

    public function confirmarAtividade?(User $user, Inscricao $inscricao, $atividadeId): bool
    {
        $atividade = $inscricao->atividades()->find($atividadeId);
        if (!$atividade) {
            return false;
        }
        // Apenas admin/coordenador podem confirmar atividades após pagamento
        return $user->perfil === 'admin' || $user->perfil === 'coordenador';
    }
}