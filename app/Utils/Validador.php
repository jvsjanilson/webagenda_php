<?php

namespace App\Utils;

use App\Utils\Contracts\Documento;
use App\Utils\CNPJ;
use App\Utils\CPF;
use App\Utils\Util;

abstract class Validador
{
    public static function validar($value) : bool
    {
        $value = Util::numberOnly($value);
        $documento = (strlen($value) == 11 ? new CPF() : new CNPJ());
        return $documento->validar($value);
    }

}
