<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\TicketCreated;
use App\Listeners\SendTelegramNotification;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        TicketCreated::class => [
            SendTelegramNotification::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
