<?php

namespace App\Listeners;

use App\Helpers\TelegramHelper;

class SendTelegramNotification
{
    public function handle($event)
    {
        $ticket = $event->ticket;

        TelegramHelper::send("
        <b>📌 Tiket Baru</b>
        No: {$ticket->ticket_number}
        Nama: {$ticket->nama}
        Deskripsi: {$ticket->description}
        ");
    }
}
