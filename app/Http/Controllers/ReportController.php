<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TicketsExport;

class ReportController extends Controller
{

    public function summary(Request $request)
    {
        $start = $request->periode_dari ?? now()->startOfMonth();
        $end = $request->periode_sampai ?? now()->endOfMonth();

        $query = Ticket::with('approval')->whereBetween('created_at', [$start, $end]);

        if ($request->kategori) {
            $query->where('category', $request->kategori);
        }
        if ($request->status_tiket) {
            $query->where('status', $request->status_tiket);
        }
        if ($request->status_approval) {
            $query->whereHas('approval', function ($q) use ($request) {
                $q->where('approval_status', $request->status_approval);
            });
        }

        $tickets = $query->get();

        // Hitung summary
        $totalTickets = $tickets->count();
        $totalApproved = $tickets->filter(fn($t) => $t->approval && $t->approval->approval_status == 'Approved')->count();
        $totalRejected = $tickets->filter(fn($t) => $t->approval && $t->approval->approval_status == 'Rejected')->count();
        $totalNeedClarification = $tickets->filter(fn($t) => $t->approval && $t->approval->approval_status == 'Need Clarification')->count();
        $totalOpen = $tickets->where('status', 'Open')->count();
        $totalInProgress = $tickets->where('status', 'In Progress')->count();
        $totalClosed = $tickets->where('status', 'Closed')->count();

        $avgResolution = Ticket::where('status', 'Closed')
            ->whereNotNull('resolved_at')
            ->whereBetween('resolved_at', [$start, $end])
            ->avg(DB::raw('TIMESTAMPDIFF(HOUR, created_at, resolved_at) / 24'));

        // Rekap per kategori
        $categoryRecap = $tickets->groupBy('category')->map->count();

        // Rekap per status
        $statusRecap = $tickets->groupBy('status')->map->count();

        return view('reports.summary', compact(
            'tickets',
            'totalTickets',
            'totalApproved',
            'totalRejected',
            'totalNeedClarification',
            'totalOpen',
            'totalInProgress',
            'totalClosed',
            'avgResolution',
            'categoryRecap',
            'statusRecap'
        ));
    }

    public function export(Request $request)
    {
        return Excel::download(new TicketsExport($request), 'laporan_helpdesk_' . date('YmdHis') . '.xlsx');
    }
}
