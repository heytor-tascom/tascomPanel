<?php
namespace App\Http\Helpers;

class Helpers
{
    public static function activeIcon($ativo)
    {
        return ($ativo) ? '<i class="fas fa-check-circle fa-lg text-success"></i>' : '<i class="fas fa-times-circle fa-lg text-danger"></i>';
    }
}