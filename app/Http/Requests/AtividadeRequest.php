<?php

namespace App\Http\Requests;

use App\Models\Atividade;
use App\Models\Edicao;
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
            'edicao_id' => ['required', 'integer', 'exists:edicoes,id'],
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['required', 'string'],
            'tipo' => ['required', Rule::in(['palestra', 'minicurso'])],
            'responsavel' => ['required', 'string', 'max:255'],
            'inicio' => ['required', 'date'],
            'duracao_minutos' => ['required', 'integer', 'min:1'],
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
            'duracao_minutos.min' => 'A duração deve ser maior que zero.',
            'capacidade.min' => 'A capacidade deve ser maior que zero.',
            'local_id.exists' => 'O local selecionado não existe.',
            'edicao_id.exists' => 'A edição selecionada não existe.',
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $local = Local::find($this->integer('local_id'));
                $edicao = Edicao::find($this->integer('edicao_id'));

                if (! $local || ! $edicao) {
                    return;
                }

                $inicio = Carbon::parse($this->input('inicio'));
                $fim = $inicio->copy()->addMinutes($this->integer('duracao_minutos'));

                if ($inicio->toDateString() < $edicao->data_inicio->toDateString()
                    || $inicio->toDateString() > $edicao->data_fim->toDateString()) {
                    $validator->errors()->add(
                        'inicio',
                        'A data da atividade deve estar dentro do período da edição selecionada.'
                    );
                }

                if ($this->integer('capacidade') > $local->capacidade) {
                    $validator->errors()->add(
                        'capacidade',
                        'A capacidade da atividade não pode ultrapassar a capacidade do local.'
                    );
                }

                $atividadeAtual = $this->route('atividade');
                $idAtual = $atividadeAtual instanceof Atividade ? $atividadeAtual->id : null;

                $atividades = Atividade::where('local_id', $local->id)
                    ->where('edicao_id', $edicao->id)
                    ->when($idAtual, fn ($query) => $query->where('id', '!=', $idAtual))
                    ->whereBetween('inicio', [$inicio->copy()->subDay(), $fim])
                    ->get();

                foreach ($atividades as $atividade) {
                    $fimExistente = $atividade->inicio->copy()
                        ->addMinutes($atividade->duracao_minutos);

                    if ($inicio->lt($fimExistente) && $fim->gt($atividade->inicio)) {
                        $validator->errors()->add(
                            'inicio',
                            'Já existe uma atividade neste local durante o horário informado.'
                        );
                        break;
                    }
                }
            },
        ];
    }
}
