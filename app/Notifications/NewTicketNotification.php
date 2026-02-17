<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewTicketNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Saluran notifikasi yang digunakan.
     */
    public function via($notifiable)
    {
        return ['database', 'mail']; // Bisa ditambah 'broadcast' jika perlu realtime
    }

    /**
     * Representasi database.
     */
    public function toDatabase($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'user_name' => $this->ticket->user->name ?? 'Unknown',
            'unit' => $this->ticket->unit,
            'category' => $this->ticket->category,
            'urgency' => $this->ticket->urgency,
            'message' => 'Tiket baru diajukan: ' . $this->ticket->ticket_number,
        ];
    }

    /**
     * Representasi email (opsional).
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Tiket Baru: ' . $this->ticket->ticket_number)
                    ->line('Tiket baru telah diajukan oleh ' . ($this->ticket->user->name ?? 'User'))
                    ->line('Kategori: ' . $this->ticket->category)
                    ->line('Urgensi: ' . $this->ticket->urgency)
                    ->action('Lihat Tiket', url('/tickets/' . $this->ticket->id))
                    ->line('Terima kasih.');
    }

    /**
     * Representasi array (untuk API response).
     */
    public function toArray($notifiable)
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_number' => $this->ticket->ticket_number,
            'message' => 'Tiket baru: ' . $this->ticket->ticket_number,
        ];
    }
}