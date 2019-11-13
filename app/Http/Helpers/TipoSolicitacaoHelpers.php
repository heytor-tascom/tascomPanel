<?php
namespace App\Http\Helpers;

class TipoSolicitacaoHelpers
{
    public static function tipoSolicitacao($tpSolicitacao)
    {
        switch($tpSolicitacao)
        {
            case "C":
            return '<i class="fas fa-reply" style="color:#c0392b;"></i>';
            break;
            
            case "E":
            return '<i class="fas fa-sync-alt" style="color:#f39c12;"></i>';
            break;
            
            case "S":
            return '<i class="fas fa-clinic-medical" style="color:#e67e22;"></i>';
            break;

            case "P":
            return '<i class="fas fa-user-injured" style="color:#16a085;"></i>';
            break;

            default:
            //return $tpSolicitacao;
            break;
        }
    }
}
