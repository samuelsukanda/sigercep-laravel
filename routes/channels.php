<?php

use Illuminate\Support\Facades\Broadcast;

try {
    Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
        return (int) $user->id === (int) $id;
    });
} catch (\Throwable $e) {
    // Broadcast not configured — skip channel registration
}
