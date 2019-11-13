<?php
namespace App\Http\Helpers;

class SituacaoSolicitacaoHelpers
{
    public static function situacaoSolicitacao($tpSituacao)
    {
        switch($tpSituacao)
        {
            // case "C":
            // return '<i class="fas fa-prescription-bottle-alt" style="color:#ffb601;"></i>';
            // break;
            
            // case "P":
            // return '<i class="fas fa-prescription-bottle-alt" style="color:red;"></i>';
            // break;
            
            // case "S":
            // return '<i class="fas fa-prescription-bottle-alt" style="color:gray;"></i>';
            // break;
            
            case "C":
            return 'icone_parcial';
            break;
            
            case "P":
            return 'icone_nao_dado';
            break;
            
            case "S":
            return 'icone_atendido';
            break;

            default:
            //return $tpSituacao;
            break;
        }
    }
}