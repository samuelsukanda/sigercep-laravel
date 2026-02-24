<?php

namespace App\Notifications;

use App\Models\TicketApproval;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

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
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'ticket_number' => $this->ticket->ticket_number,
            'approval_status' => $this->approval->approval_status,
            'message' => 'Tiket ' . $this->ticket->ticket_number . '  ' . $this->approval->approval_status,
            'url' => route('helpdesk.show', $this->ticket->id),
        ];
    }

    public function toDatabase($notifiable)
    {
        return [
            'ticket_number' => $this->ticket->ticket_number,
            'approval_status' => $this->approval->approval_status,
            'message' => 'Tiket ' . $this->ticket->ticket_number . ' ' . $this->approval->approval_status,
            'url' => route('helpdesk.show', $this->ticket->id),
        ];
    }
}
