<?php

namespace App\Utils;

use App\Utils\Contracts\Documento;
use App\Utils\Util;

class CPF implements Documento
{
    public function Validar($value) : bool
    {
        $novo_cpf = Util::calc_digitos_posicoes( Util::calc_digitos_posicoes( substr($value, 0, 9) ), 11 );
        return  $novo_cpf === $value && Util::verifica_sequencia(11, $novo_cpf) ? true : false;
    }

}
