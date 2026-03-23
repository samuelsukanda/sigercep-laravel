<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markAsRead($id)
    {
        $notification = DatabaseNotification::findOrFail($id);
        if ($notification->notifiable_id !== Auth::id()) {
            abort(403);
        }

        $notification->markAsRead();

        return back()->with('success');
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('success');
    }

    public function go($id)
    {
        $notification = DatabaseNotification::findOrFail($id);
        if ($notification->notifiable_id == Auth::id()) {
            $notification->markAsRead();
        }
        return redirect($notification->data['url'] ?? '/');
    }
}
