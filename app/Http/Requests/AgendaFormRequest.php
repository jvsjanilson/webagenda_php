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
            'dt_agenda' => ['required'],
            'numero_pedido' => ['required']
        ];
    }

    public function attributes()
    {
        return [
            'dt_agenda' => 'Data',
            'numero_pedido' => 'Núm. Pedido'
        ];
    }
}
