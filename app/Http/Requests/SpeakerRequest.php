<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpeakerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'institution' => ['nullable', 'string', 'max:255'],
            'academic_title' => ['nullable', 'string', 'max:255'],
            'bio' => ['required', 'string', 'min:10'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome do palestrante é obrigatório.',
            'name.string' => 'O nome deve ser um texto válido.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'institution.string' => 'A instituição deve ser um texto válido.',
            'institution.max' => 'A instituição não pode ter mais de 255 caracteres.',
            'academic_title.string' => 'A formação/titulação deve ser um texto válido.',
            'academic_title.max' => 'A formação/titulação não pode ter mais de 255 caracteres.',
            'bio.required' => 'O minicurrículo é obrigatório.',
            'bio.string' => 'O minicurrículo deve ser um texto válido.',
            'bio.min' => 'O minicurrículo deve ter pelo menos 10 caracteres.',
        ];
    }
}
