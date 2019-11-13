<?php
namespace App\Http\Helpers;

class SolicitacaoAvulsaHelpers
{
    public static function avulsaSolicitacao($solicAvulsa)
    {
        switch($solicAvulsa)
        {
            case "AVU":
            return '<i class="fas fa-tablets" style="color:#34495e;"></i>';
            break;

            default:
            
            break;
        }
    }
}
