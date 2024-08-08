<?php

namespace App\Utils;

use App\Models\Limite;
use App\Models\Config;
use Carbon\Carbon;


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

    public static function getIntervaloDatas($dataInicial, $dataFinal) : array
    {
        $intervalo = [];
        $dataInicial = Carbon::parse($dataInicial);
        $dataFinal = Carbon::parse($dataFinal);
        while ($dataInicial <= $dataFinal) {
            $intervalo[] = $dataInicial->toDateString();
            $dataInicial->addDay();
        }

        return $intervalo;
    }

    public static function getLimiteEntrega($dataInicial, $dataFinal = null, $tipoAgenda = 'E') : int
    {
        if (is_null($dataFinal)) {
            $dataFinal = $dataInicial;
        }

        $totalLimite = 0;
        foreach (Util::getIntervaloDatas($dataInicial, $dataFinal) as $data) {
            if ( Limite::where('dt_limite', $data)->where('tipo_agenda', $tipoAgenda)->exists() ) {
                $totalLimite += Limite::where('dt_limite', $data)->where('tipo_agenda', $tipoAgenda)->first()->limite;
            } else {
                $totalLimite += $tipoAgenda == 'E' ? Config::first()->limite_entrega : Config::first()->limite_montagem;
            }
        }
        return $totalLimite;
    }

}

