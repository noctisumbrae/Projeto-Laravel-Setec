<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LocalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:255'],
            'tipo' => [
                'required',
                Rule::in(['sala', 'laboratorio', 'auditorio']),
            ],
            'capacidade' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'Informe o nome do local.',
            'tipo.required' => 'Selecione o tipo do local.',
            'tipo.in' => 'Selecione um tipo válido.',
            'capacidade.required' => 'Informe a capacidade.',
            'capacidade.integer' => 'A capacidade deve ser um número inteiro.',
            'capacidade.min' => 'A capacidade deve ser maior que zero.',
        ];
    }
}