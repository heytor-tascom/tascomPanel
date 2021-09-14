<?php
namespace App\Http\Helpers;

use Carbon\Carbon;

class SolSaiProHelpers
{

    public static function overdue($dateTimePriNecessidade, $dateTimeSolsaiPro, $type, $snUrgente, $snAtendida)
    {
        $dateTimePriNecessidade != null ? Carbon::createFromFormat('d/m/Y H:i:s', $dateTimePriNecessidade)->format('d/m/Y H:i:s') : false;
        $dateTimeSolsaiPro = Carbon::createFromFormat('d/m/Y H:i:s', $dateTimeSolsaiPro)->format('d/m/Y H:i:s');
        $dateTimeNow = date('d/m/Y H:i:s');

        if (($type == 'PRE' || $type == null) && $snUrgente == 'N' && $snAtendida == ''){
            return $dateTimePriNecessidade < $dateTimeNow ? '<i class="fas fa-clock text-danger"></i>' : '';

        }

        if (($type == 'PRE' || $type == null) && $snUrgente == 'S' && $snAtendida == ''){
            $dateTimeWithMinutes = Carbon::now()->subMinute(40)->format('d/m/Y H:i:s');
            return $dateTimePriNecessidade < $dateTimeWithMinutes ? "<i class='fas fa-clock text-danger'></i>" : "";

        }


        if (($type == 'AVU') && $snAtendida == ''){
            $dateTimeWithMinutes = Carbon::now()->subMinute(40)->format('d/m/Y H:i:s');
            return $dateTimeSolsaiPro < $dateTimeWithMinutes ? "<i class='fas fa-clock text-danger'></i>" : "";

        }
    }
}
