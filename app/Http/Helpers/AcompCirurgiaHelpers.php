<?php
namespace App\Http\Helpers;

class AcompCirurgiaHelpers
{

    public static function statusBloco($statusBloco)
    {
        switch($statusBloco)
        {
            case 1:
            return '<i class="fas fa-arrow-right" style="color:#4C6EF5;"></i>';
            break;

            default: return '<i class="fas fa-arrow-right" style="color:#D50000;"></i>';

            break;
        }
    }

    public static function statusRPA($statusRPA)
    {
        switch($statusRPA)
        {
            case 1:
            return '<i class="fas fa-arrow-right" style="color:#4C6EF5;"></i>';
            break;

            default: return '<i class="fas fa-arrow-right" style="color:#D50000;"></i>';

            break;
        }
    }

    public static function statusLeito($statusLeito)
    {
        switch($statusLeito)
        {
            case 1:
            return '<i class="fas fa-arrow-right" style="color:#4C6EF5;"></i>';
            break;

            default: return '<i class="fas fa-arrow-right" style="color:#D50000;"></i>';

            break;
        }
    }

    public static function status($status)
    {
        switch($status)
        {
            case 'AD':
            return '<i class="fas fa-arrow-left" style="color:#4C6EF5;"></i>';
            break;

            case 'HO':
            return '<i class="fas fa-circle" style="color:green;"></i>';
            break;

            case 'AT':
            return '<i class="fas fa-circle" style="color:#D50000;"></i>';
            break;

            case 'C':
            return '<i class="far fa-times-circle" style="color:#D50000;"></i>';
            break;

            default:

            break;
        }
    }

    public static function status_doc_c($status_doc_c)
    {
        switch($status_doc_c)
        {
            case 'S':
            return '<i class="fas fa-file-contract" style="color:green;"></i>';
            break;

            case 'N':
            return '<i class="fas fa-file-contract" style="color:#D50000;"></i>';
            break;

            default: '<i class="fas fa-file-contract" style="color:#D50000;"></i>'; 

            break;
        }
    }


    public static function admissao($admissao)
    {
        
        if ($admissao == 'S'){
            return '<i class="fas fa-notes-medical" style="color:green;"></i>';
        }else{
            return '<i class="fas fa-notes-medical" style="color:#D50000;"></i>';
        }
    }    
}
