<?php
namespace App\Http\Helpers;

class Helpers
{
    public static function getIdade($minDate, $maxDate = null)
    {
        // Separa em dia, mês e ano
        list($dia, $mes, $ano) = explode('/', $minDate);
        
        // Descobre que dia é hoje e retorna a unix timestamp
        $hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        // Descobre a unix timestamp da data de nascimento do fulano
        $nascimento = mktime( 0, 0, 0, $mes, $dia, $ano);
        
        // Depois apenas fazemos o cálculo já citado :)
        $idade = floor((((($hoje - $nascimento) / 60) / 60) / 24) / 365.25);
        
        return intval($idade);
    }

    public static function diffDate($minDate, $maxDate = null, $type = "d")
    {
        // Separa em dia, mês e ano
        list($dia, $mes, $ano) = explode('/', $minDate);
        
        // Descobre que dia é hoje e retorna a unix timestamp
        $hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        // Descobre a unix timestamp da data de nascimento do fulano
        $nascimento = mktime( 0, 0, 0, $mes, $dia, $ano);
        
        switch($type) {
            //anos
            case 'a':
            $response = floor((((($hoje - $nascimento) / 60) / 60) / 24) / 365.25);
            break;
            
            //dias
            case 'd':
            $response = floor((((($hoje - $nascimento) / 60) / 60) / 24));
            break;
            
            //dias
            default:
            $response = null;
            break;
        }	
        
        return $response;
    }

    public static function abreviarNome($nome)
    {
        $nomes = explode(' ', ltrim(rtrim($nome)));

        $abreviacao = [];

        foreach($nomes as $key => $value) {
                
            if($key === 0) {
                $abreviacao[] = $value;
            } else {
                if (!empty($value)) {
                    $abreviacao[] = substr($value, 0, 1).".";
                }
            }
            
        }

        return implode(" ", array_filter($abreviacao));
    }
    
    public static function activeIcon($ativo)
    {
        return ($ativo) ? '<i class="fas fa-check-circle fa-lg text-success"></i>' : '<i class="fas fa-times-circle fa-lg text-danger"></i>';
    }

    public static function retirarAcentos(String $string)
    {
        $string = str_replace( array(' ', 'à','á','â','ã','ä', 'ç', 'è','é','ê','ë', 'ì','í','î','ï', 'ñ', 'ò','ó','ô','õ','ö', 'ù','ú','û','ü', 'ý','ÿ', 'À','Á','Â','Ã','Ä', 'Ç', 'È','É','Ê','Ë', 'Ì','Í','Î','Ï', 'Ñ', 'Ò','Ó','Ô','Õ','Ö', 'Ù','Ú','Û','Ü', 'Ý'), array('_', 'a','a','a','a','a', 'c', 'e','e','e','e', 'i','i','i','i', 'n', 'o','o','o','o','o', 'u','u','u','u', 'y','y', 'A','A','A','A','A', 'C', 'E','E','E','E', 'I','I','I','I', 'N', 'O','O','O','O','O', 'U','U','U','U', 'Y'), $string); 

        return $string;
    }
}