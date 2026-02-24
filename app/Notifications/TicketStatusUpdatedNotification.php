<?php

namespace App\Notifications;

use App\Models\TicketUpdate;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TicketStatusUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $ticketUpdate;

    public function __construct(TicketUpdate $ticketUpdate)
    {
        $this->ticketUpdate = $ticketUpdate;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        $ticket = $this->ticketUpdate->ticket;
        return [
            'ticket_id' => $ticket->id,
            'ticket_number' => $ticket->ticket_number,
            'new_status' => $this->ticketUpdate->status,
            'note' => $this->ticketUpdate->note,
            'updated_by' => $this->ticketUpdate->user->name ?? 'System',
            'message' => 'Status tiket ' . $ticket->ticket_number . ' berubah menjadi ' . $this->ticketUpdate->status,
            'url' => route('helpdesk.show', $ticket->id),
        ];
    }

    public function toMail($notifiable)
    {
        $ticket = $this->ticketUpdate->ticket;
        return (new MailMessage)
            ->subject('Update Status Tiket: ' . $ticket->ticket_number)
            ->line('Status tiket Anda (' . $ticket->ticket_number . ') telah diperbarui menjadi: ' . $this->ticketUpdate->status)
            ->line('Catatan: ' . ($this->ticketUpdate->note ?? '-'))
            ->action('Lihat Tiket', url('/tickets/' . $ticket->id))
            ->line('Terima kasih.');
    }

    public function toArray($notifiable)
{
    $ticket = $this->ticketUpdate->ticket;
    return [
        'ticket_id' => $ticket->id,
        'ticket_number' => $ticket->ticket_number,
        'new_status' => $this->ticketUpdate->status,
        'note' => $this->ticketUpdate->note,
        'updated_by' => $this->ticketUpdate->user->name ?? 'System',
        'message' => 'Status tiket ' . $ticket->ticket_number . ' berubah menjadi ' . $this->ticketUpdate->status,
        'url' => route('helpdesk.show', $ticket->id), // untuk user
    ];
}
}
