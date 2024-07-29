<?php

namespace App\Http\Requests;

use App\Utils\Validador;
use Illuminate\Foundation\Http\FormRequest;

class PagamentoFormRequest extends FormRequest
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
            'nome' => ['required', 'max:60'],
            'cpfcnpj' => ['required', function($attribute, $value, $fail) {
                if ($value != "") {
                    if (!Validador::validar($value)) {
                        $fail(':attribute inválido.');
                    }
                }
            }]
        ];
    }

    public function attributes()
    {
        return [
            'nome' => 'Nome / Razão Social',
            'cpfcnpj' => 'CPF/CNPj'
        ];
    }
}
