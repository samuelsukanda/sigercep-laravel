<?php

namespace App\Helpers;

use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class TicketHelper
{
    public static function generateTicketNumber()
    {
        $month = now()->format('m');
        $year  = now()->format('Y');
        $prefix = $month . '-' . $year;

        $lastTicket = DB::table('tickets')
            ->where('ticket_number', 'LIKE', "%-{$prefix}")
            ->lockForUpdate()
            ->orderByDesc('id')
            ->first();

        if ($lastTicket) {
            $lastNumber = explode('-', $lastTicket->ticket_number)[0];
            $newNumber = str_pad((int)$lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }

        return $newNumber . '-' . $prefix;
    }
}
