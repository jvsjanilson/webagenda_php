<?php

namespace App\Utils;


abstract class Util
{

    public static $mensalidade = 1.00;
    /**
     * Retorna as credenciais do gerencia net
     */
    public static function credentials()
    {

        $file = storage_path() . '/app/private/producao-429531-manifesto-online.pem';

        $options = [
            "clientId" => env('GN_CHAVE_CLIENT_ID'),
            "clientSecret" => env('GN_CHAVE_SECRET'),
            "certificate" => $file,
            //"pwdCertificate" => "", optional
            "sandbox" => false,
            "debug" => false,
            "timeout" => 30
        ];

        return $options;
    }

    public static function numberOnly($value)
    {
        return preg_replace('/\D/', '', $value);
    }

    public static function calc_digitos_posicoes( $digitos, $posicoes = 10, $soma_digitos = 0 ) {
		for ( $i = 0; $i < strlen( $digitos ); $i++  ) {
			$soma_digitos = $soma_digitos + ( $digitos[$i] * $posicoes );
			$posicoes--;
			if ( $posicoes < 2 ) {
				$posicoes = 9;
			}
		}

		$soma_digitos = $soma_digitos % 11;

    	if ( $soma_digitos < 2 ) {
			$soma_digitos = 0;
		} else {
			$soma_digitos = 11 - $soma_digitos;
		}

		$result = $digitos . $soma_digitos;

		return $result;
	}

    public static function verifica_sequencia($multiplos, $value)
	{
	    for($i=0; $i<10; $i++) {
			if (str_repeat($i, $multiplos) == $value) {
				return false;
			}
		}
		return true;
	}

    /**
     * Formata date em data no formato especificado, que vem com o formato padrao brasileiro
     * @param date $data
     * @param string $format
     * @return string
     */
    public static function formatDateBr($date, $format = 'd/m/Y')
    {
        return is_null($date) ? '' : date($format,  strtotime($date));
    }

    /**
     * Formata data formato brasileiro em formato americano
     * Exemplo: 15/12/2021 -> 2021-12-15
     * @param string $value
     * @return date
     */
    public static function formatBrDate($value, $format = 'Y-m-d')
    {
        return ($value != "") ? date($format,  strtotime(str_replace('/', '-', $value))) : date('Y-m-d');
    }
}
