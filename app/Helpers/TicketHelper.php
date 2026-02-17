<?php

namespace App\Helpers;

use App\Models\Ticket;

class TicketHelper
{
    public static function generateTicketNumber()
    {
        $month = now()->format('m');
        $year = now()->format('y');
        $prefix = $month . '-' . $year; // contoh: 02-26

        $lastTicket = Ticket::where('ticket_number', 'LIKE', "%-{$prefix}")
            ->orderBy('id', 'desc')
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
