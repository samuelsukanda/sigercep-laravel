<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class TicketsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents, WithCustomStartCell
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        return Ticket::with(['user', 'approval'])
            ->filter($this->request)
            ->get();
    }

    public function headings(): array
    {
        return [
            [
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
            ]
        ];
    }

    public function map($ticket): array
    {
        return [
            $ticket->ticket_number,
            $ticket->created_at->format('d-m-Y H:i'),
            ucwords(strtolower($ticket->user->name ?? '-')),
            $ticket->unit_name ?? '-',
            $ticket->category ?? '-',
            $ticket->description,
            $ticket->status,
            $ticket->approval?->approval_status ?? 'Pending',
            $ticket->approval?->approved_by ?? '-',
            $ticket->approval?->duration ?? '-',
            $ticket->resolved_at ? $ticket->resolved_at->format('d-m-Y') : '-',
        ];
    }

    public function startCell(): string
    {
        return 'A2';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                // ========================
                // 🔷 JUDUL
                // ========================
                $sheet->mergeCells('A1:K1');
                $sheet->setCellValue('A1', 'Laporan Helpdesk IT');

                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 20,
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical' => 'center',
                    ],
                ]);

                $sheet->getRowDimension(1)->setRowHeight(30);

                // ========================
                // 🔷 HEADER
                // ========================
                $sheet->getStyle('A2:K2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                    ],
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical' => 'center',
                    ],
                    'fill' => [
                        'fillType' => 'solid',
                        'color' => ['rgb' => 'FFC000'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => 'thin',
                        ],
                    ],
                ]);

                // ========================
                // 🔥 AUTO FILTER + FREEZE
                // ========================
                $sheet->setAutoFilter('A2:K2');
                $sheet->freezePane('A3');

                // ========================
                // 🔷 DATA (SEMUA CENTER + MIDDLE)
                // ========================
                $highestRow = $sheet->getHighestRow();

                $sheet->getStyle("A3:K{$highestRow}")->applyFromArray([
                    'alignment' => [
                        'horizontal' => 'center',
                        'vertical' => 'center',
                    ],
                ]);

                // ========================
                // 🦓 ZEBRA ROW
                // ========================
                for ($row = 3; $row <= $highestRow; $row++) {
                    if ($row % 2 == 0) {
                        $sheet->getStyle("A{$row}:K{$row}")->applyFromArray([
                            'fill' => [
                                'fillType' => 'solid',
                                'color' => ['rgb' => 'F9F9F9'],
                            ],
                        ]);
                    }
                }

                // ========================
                // 🔷 BORDER
                // ========================
                $sheet->getStyle("A2:K{$highestRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle('thin');
            },
        ];
    }
}
