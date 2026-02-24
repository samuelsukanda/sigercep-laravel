<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TicketsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        // Query sama dengan di summary, bisa di-refactor
        $query = Ticket::with(['user', 'approval']);
        // Filter berdasarkan request
        if ($this->request->filled('periode_dari')) {
            $query->whereDate('created_at', '>=', $this->request->periode_dari);
        }
        if ($this->request->filled('periode_sampai')) {
            $query->whereDate('created_at', '<=', $this->request->periode_sampai);
        }
        // ... filter lainnya
        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No. Tiket',
            'Tanggal/Jam',
            'Nama Pelapor',
            'Divisi',
            'Kategori',
            'Deskripsi',
            'Status Tiket',
            'Status Approval',
            'Approved By',
            'Estimasi Penyelesaian',
            'Tanggal Selesai',
        ];
    }

    public function map($ticket): array
    {
        return [
            $ticket->ticket_number,
            $ticket->created_at->format('d-m-Y H:i'),
            ucwords(strtolower($ticket->user->name)),
            $ticket->unit,
            $ticket->category,
            $ticket->description,
            $ticket->status,
            $ticket->approval->approval_status ?? 'Pending',
            $ticket->approval->approved_by ?? '-',
            $ticket->approval ? $ticket->approval->duration : '-',
            $ticket->resolved_at ? $ticket->resolved_at->format('d-m-Y') : '-',
        ];
    }
}
