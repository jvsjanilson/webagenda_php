<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgendaFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'dt_agenda' => ['required'],
            'numero_pedido' => ['required', 'max:50'],
            'no_minimo' => [function($attribute, $value, $fail){
                if ((int)$value == 0 && (int)$this->request->get('ligar_antes') == 1) {
                    $fail('O :attribute deve ser informado!');
                }
                if ((int)$value > 0 && (int)$this->request->get('ligar_antes') == 0) {
                    $fail('Escolha a opção SEM HORÁRIO MÍNIMO');
                }

            }],
        ];
    }

    public function attributes()
    {
        return [
            'dt_agenda' => 'Data',
            'numero_pedido' => 'Núm. Pedido / Nome do cliente'
        ];
    }
}
