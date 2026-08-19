<?php

namespace App\Broadcasting;

use Illuminate\Broadcasting\Broadcasters\PusherBroadcaster;
use Illuminate\Broadcasting\BroadcastException;

class SafeBroadcaster extends PusherBroadcaster
{
    public function broadcast(array $channels, $event, array $payload = [])
    {
        try {
            parent::broadcast($channels, $event, $payload);
        } catch (BroadcastException $e) {
            // Reverb tidak aktif — abaikan broadcast, notifikasi DB tetap tersimpan.
        }
    }
}