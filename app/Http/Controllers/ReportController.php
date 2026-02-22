<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TicketsExport;

class ReportController extends Controller
{

    public function summary(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'periode_dari'   => 'nullable|date_format:d-m-Y',
            'periode_sampai' => 'nullable|date_format:d-m-Y|after_or_equal:periode_dari',
        ], [
            'periode_sampai.after_or_equal' => 'Periode Sampai harus lebih besar atau sama dengan Periode Dari.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $start = $request->filled('periode_dari')
            ? Carbon::createFromFormat('d-m-Y', $request->periode_dari)->startOfDay()
            : now()->startOfMonth();

        $end = $request->filled('periode_sampai')
            ? Carbon::createFromFormat('d-m-Y', $request->periode_sampai)->endOfDay()
            : now()->endOfMonth();

        $query = Ticket::with(['user', 'approval'])->whereBetween('created_at', [$start, $end]);

        if ($request->filled('kategori')) {
            $query->where('category', $request->kategori);
        }
        if ($request->filled('status_tiket')) {
            $query->where('status', $request->status_tiket);
        }
        if ($request->filled('status_approval')) {
            $query->whereHas('approval', function ($q) use ($request) {
                $q->where('approval_status', $request->status_approval);
            });
        }

        $tickets = $query->get();
        $ticket_approvals = $tickets->pluck('approval')->filter();

        // Hitung ringkasan
        $totalTickets = $tickets->count();
        $totalApproved = $ticket_approvals->where('approval_status', 'Approved')->count();
        $totalRejected = $ticket_approvals->where('approval_status', 'Rejected')->count();
        $totalNeedClarification = $ticket_approvals->where('approval_status', 'Need Clarification')->count();

        $totalOpen = $tickets->where('status', 'Open')->count();
        $totalInProgress = $tickets->where('status', 'In Progress')->count();
        $totalClosed = $tickets->where('status', 'Closed')->count();
        $totalResolved = $tickets->filter(fn($t) => !is_null($t->resolved_at))->count();

        // Rata-rata waktu penyelesaian (berdasarkan resolved_at)
        $avgResolution = Ticket::where('status', 'Closed')
            ->whereNotNull('resolved_at')
            ->whereBetween('resolved_at', [$start, $end])
            ->selectRaw('avg(TIMESTAMPDIFF(HOUR, created_at, resolved_at) / 24) as avg_resolution')
            ->value('avg_resolution') ?? 0;

        // Rekap per kategori
        $categoryRecap = $tickets->groupBy('category')->map->count();

        // Rekap per status approval
        $approvalRecap = $ticket_approvals->groupBy('approval_status')->map->count();

        // Rekap per status tiket
        $statusRecap = $tickets->groupBy('status')->map->count();
        $statusRecap['Resolved'] = $totalResolved;

        return view('pages.helpdesk.reports.summary', compact(
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
            'statusRecap',
            'approvalRecap'
        ));
    }

    public function export(Request $request)
    {
        return Excel::download(new TicketsExport($request), 'laporan_helpdesk_' . date('YmdHis') . '.xlsx');
    }
}
