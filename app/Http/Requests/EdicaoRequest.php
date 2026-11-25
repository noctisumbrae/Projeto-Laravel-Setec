<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EdicaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        // A Equipe 6 vai integrar o controle de acesso (perfil administrador)
        return true;
    }

    public function rules(): array
    {
        $edicao = $this->route('edicao');

        return [
            'nome' => ['required', 'string', 'max:150'],
            'ano' => [
                'required',
                'integer',
                'between:2000,2100',
                Rule::unique('edicoes', 'ano')->ignore($edicao?->id),
            ],
            'data_inicio' => ['required', 'date'],
            'data_fim' => ['required', 'date', 'after_or_equal:data_inicio'],
            'descricao' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $anoInicio = (int) date('Y', strtotime($this->input('data_inicio')));

            if ($anoInicio !== (int) $this->input('ano')) {
                $validator->errors()->add('data_inicio', 'A data de início deve estar dentro do ano informado.');
            }
        });
    }

    public function attributes(): array
    {
        return [
            'nome' => 'nome',
            'ano' => 'ano',
            'data_inicio' => 'data de início',
            'data_fim' => 'data de término',
            'descricao' => 'descrição',
        ];
    }
}