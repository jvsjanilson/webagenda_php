<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgendaEntregaFormRequest extends FormRequest
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
            'empresa_id' => ['required'],
            'numero_pedido' => ['required', 'max:50'],
            'dt_agenda' => 'required',
            'no_minimo' => [function($attribute, $value, $fail){
                if ((int)$value == 0 && (int)$this->request->get('ligar_antes') == 1) {
                    $fail('O :attribute deve ser informado!');
                }
                if ((int)$value > 0 && (int)$this->request->get('ligar_antes') == 0) {
                    $fail('Escolha a opção SEM HORÁRIO MÍNIMO');
                }

            }],
            'valor' => [function($attribute, $value, $fail){
                $valor = (float) str_replace(',', '.', str_replace('.', '', ($value ?? '0,00')));
                if ((int)$this->request->get('pagamento') > 0) {
                    if ($valor <= 0.0) {
                        $fail('O :attribute deve ser informado.');
                    }
                }
            }],
            'valor_frete' => [function($attribute, $value, $fail){
                $valor_frete = (float) str_replace(',', '.', str_replace('.', '', ($value ?? '0,00')));
                if ($this->request->get('frete') == '1') {
                    if ($valor_frete <= 0.0) {
                        $fail('O :attribute deve ser informado.');
                    }
                }
            }],
            'ligar_antes' => ['required'],
            'periodo' => ['required'],
            'pagamento' => ['required'],
            'frete' => ['required'],
            'domicilio' => ['required'],

        ];
    }

    public function attributes()
    {
        return [
            'numero_pedido' => 'Núm. Pedido / Nome do cliente',
            'dt_agenda' => 'Data',
            'valor' => 'Valor',
            'valor_frete' => 'Valor Frete',
            'pagamento' => 'Pagamento na entrega',
            'empresa_id' => 'Loja',
            'no_minimo' => 'No mínimo',
            'ligar_antes' => 'Ligar Antes?',
            'periodo' => 'Período',
            'frete' => 'Frete?',
            'domicilio' => 'Domicílio',
        ];
    }
}
