<?php

namespace App\Utils;

use App\Utils\Contracts\Documento;
use App\Utils\Util;

class CNPJ implements Documento
{
    public function Validar($value) : bool
    {
		$novo_cnpj = Util::calc_digitos_posicoes( Util::calc_digitos_posicoes( substr( $value, 0, 12 ) , 5 ), 6 );
		return $novo_cnpj === $value  && Util::verifica_sequencia(14, $novo_cnpj) ? true : false;
    }
}
