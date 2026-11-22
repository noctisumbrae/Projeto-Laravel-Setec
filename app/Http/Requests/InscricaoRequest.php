<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InscricaoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'edicao_id' => 'required|exists:edicoes,id',
            'status' => 'sometimes|string|in:pendente,confirmada,cancelada',
            'atividade_id' => 'sometimes|exists:atividades,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'user_id.exists' => 'Usuário não encontrado.',
            'edicao_id.exists' => 'Edição não encontrada.',
            'status.in' => 'Status inválido. Use: pendente, confirmada ou cancelada.',
            'atividade_id.exists' => 'Atividade não encontrada.',
        ];
    }
}