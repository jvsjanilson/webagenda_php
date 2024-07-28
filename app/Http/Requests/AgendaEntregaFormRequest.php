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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'numero_pedido' => 'required',
            'dt_agenda' => 'required',
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
            'empresa_id' => ['required']
        ];
    }

    public function attributes()
    {
        return [
            'numero_pedido' => 'Núm. Pedido',
            'dt_agenda' => 'Data',
            'valor' => 'Valor',
            'valor_frete' => 'Valor Frete',
            'pagamento' => 'Forma de Pagto',
            'empresa_id' => 'Loja'
        ];
    }
}
