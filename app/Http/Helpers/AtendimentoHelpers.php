<?php
namespace App\Http\Helpers;

class AtendimentoHelpers
{
    public static function tipoAtendimento($tpAtendimento)
    {
        switch($tpAtendimento)
        {
            case "U":
            return "Urgência";
            break;
            
            case "I":
            return "Internação";
            break;
            
            case "A":
            return "Ambulatório";
            break;
            
            case "E":
            return "Externo";
            break;
            
            default:
            return $tpAtendimento;
            break;
        }
    }
}