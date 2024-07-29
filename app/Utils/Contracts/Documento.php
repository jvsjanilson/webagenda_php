<?php

namespace App\Utils\Contracts;

interface Documento
{
    function validar($value) : bool;
}
