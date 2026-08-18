<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ChangeRequestApprovalNotification extends Notification
{
    use Queueable;

    protected $changeRequest;
    protected $message;

    public function __construct($changeRequest, $message)
    {
        $this->changeRequest = $changeRequest;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->message,
            'url' => route('change-request.show', $this->changeRequest->id),
        ];
    }
}