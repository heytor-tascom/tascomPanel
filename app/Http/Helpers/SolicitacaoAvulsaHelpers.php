<?php
namespace App\Http\Helpers;

class SolicitacaoAvulsaHelpers
{
    public static function avulsaSolicitacao($solicAvulsa)
    {
        switch($solicAvulsa)
        {
            case "AVU":
            // return '<i class="fas fa-tablets" style="color:#34495e;"></i>';
            return '<i class="fas fa-tablets text-info"></i>';
            break;

            default:
            
            break;
        }
    }
}
