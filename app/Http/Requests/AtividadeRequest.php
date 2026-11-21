<?php

namespace App\Http\Requests;

use App\Models\Atividade;
use App\Models\Local;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class AtividadeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['required', 'string'],
            'tipo' => ['required', Rule::in(['palestra', 'minicurso'])],
            'responsavel' => ['required', 'string', 'max:255'],
            'data' => ['required', 'date'],
            'horario' => ['required', 'date_format:H:i'],
            'duracao' => ['required', 'integer', 'min:1'],
            'capacidade' => ['required', 'integer', 'min:1'],
            'local_id' => ['required', 'exists:locals,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'titulo.max' => 'O título deve ter no máximo 255 caracteres.',
            'tipo.in' => 'Selecione palestra ou minicurso.',
            'horario.date_format' => 'Informe um horário válido.',
            'duracao.min' => 'A duração deve ser maior que zero.',
            'capacidade.min' => 'A capacidade deve ser maior que zero.',
            'local_id.exists' => 'O local selecionado não existe.',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $local = Local::find($this->input('local_id'));

                if (!$local) {
                    return;
                }

                $capacidade = (int) $this->input('capacidade');

                if ($capacidade > $local->capacidade) {
                    $validator->errors()->add(
                        'capacidade',
                        'A capacidade da atividade não pode ultrapassar a capacidade do local.'
                    );
                    return;
                }

                $data = $this->input('data');
                $horario = $this->input('horario');
                $duracao = (int) $this->input('duracao');

                $inicio = Carbon::parse("$data $horario");
                $fim = $inicio->copy()->addMinutes($duracao);

                $atividadeAtual = $this->route('atividade');

                $idAtual = $atividadeAtual instanceof Atividade
                    ? $atividadeAtual->id
                    : null;

                $atividades = Atividade::where('local_id', $local->id)
                    ->when($idAtual, fn ($query) => $query->where('id', '!=', $idAtual))
                    ->whereBetween('data', [
                        $inicio->copy()->subDay()->toDateString(),
                        $fim->toDateString()
                    ])
                    ->get();

                foreach ($atividades as $atividade) {
                    $inicioExistente = Carbon::parse(
                        $atividade->data->format('Y-m-d') . ' ' . $atividade->horario
                    );

                    $fimExistente = $inicioExistente->copy()
                        ->addMinutes($atividade->duracao);

                    if ($inicio->lt($fimExistente) && $fim->gt($inicioExistente)) {
                        $validator->errors()->add(
                            'horario',
                            'Já existe uma atividade neste local durante o horário informado.'
                        );
                        break;
                    }
                }
            },
        ];
    }
}