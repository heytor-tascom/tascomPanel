<?php
namespace App\Http\Helpers;

class SolicitacaoControladosHelpers
{
    public static function controladosSolicitacao($solicControlados)
    {
        switch($solicControlados)
        {
            case "S":
            return '<i class="fas fa-capsules text-warning"></i>';
            break;

            default:
            
            break;
        }
    }
}
