<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TicketApprovalNotification extends Notification
{
    use Queueable;

    protected $ticket, $approval;

    public function __construct($ticket, $approval)
    {
        $this->ticket = $ticket;
        $this->approval = $approval;
    }

    public function via($notifiable)
    {
        if ($this->approval->approval_status === 'Approved') {
            return [];
        }

        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'ticket_number' => $this->ticket->ticket_number,
            'approval_status' => $this->approval->approval_status,
            'message' => 'Status tiket ' . $this->ticket->ticket_number . '  ' . $this->approval->approval_status,
            'url' => route('helpdesk.show', $this->ticket->id),
        ];
    }

    public function toDatabase($notifiable)
    {
        return [
            'ticket_number' => $this->ticket->ticket_number,
            'approval_status' => $this->approval->approval_status,
            'message' => 'Status tiket ' . $this->ticket->ticket_number . ' ' . $this->approval->approval_status,
            'url' => route('helpdesk.show', $this->ticket->id),
        ];
    }
}
