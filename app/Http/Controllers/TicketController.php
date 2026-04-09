<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Helpers\TicketHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\NewTicketNotification;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class TicketController extends Controller
{

    public function index(Request $request)
    {
        $isFiltered = $request->hasAny([
            'periode_dari',
            'periode_sampai',
            'kategori',
            'urgency',
            'status_tiket',
            'status_approval'
        ]);

        $validator = Validator::make($request->all(), [
            'periode_dari'   => 'nullable|date_format:d-m-Y',
            'periode_sampai' => 'nullable|date_format:d-m-Y|after_or_equal:periode_dari',
        ], [
            'periode_sampai.after_or_equal' => 'Periode Sampai harus lebih besar atau sama dengan Periode Dari.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('helpdesk.index')
                ->withErrors($validator)
                ->withInput();
        }

        $start = $request->filled('periode_dari')
            ? Carbon::createFromFormat('d-m-Y', $request->periode_dari)->startOfDay()
            : now()->startOfMonth();

        $end = $request->filled('periode_sampai')
            ? Carbon::createFromFormat('d-m-Y', $request->periode_sampai)->endOfDay()
            : now()->endOfMonth();

        $query = Ticket::with(['user', 'approval'])
            ->where('user_id', Auth::id());

        if ($request->filled('periode_dari')) {
            $query->whereDate('created_at', '>=', $start);
        }
        if ($request->filled('periode_sampai')) {
            $query->whereDate('created_at', '<=', $end);
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(15);

        $tickets->appends($request->query());

        return view('pages.helpdesk.index', compact('tickets', 'isFiltered'));
    }

    public function create()
    {
        return view('pages.helpdesk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category'    => 'nullable|in:Hardware,Printer,Jaringan,Software,SIMRS',
            'description' => 'required|string',
            'urgency'     => 'nullable|in:Low,Medium,High,Critical',
            'attachment'  => 'nullable|array',
            'attachment.*' => 'file|mimes:jpg,png,jpeg,doc,docx,xls,xlsx,pdf|max:2048'
        ]);

        $ticket = new Ticket();
        $ticket->ticket_number = TicketHelper::generateTicketNumber();
        $ticket->user_id       = Auth::id();
        $ticket->category      = $request->category;
        $ticket->description   = $request->description;
        $ticket->urgency       = $request->urgency;
        $ticket->status        = 'Open';

        if ($request->hasFile('attachment')) {
            $paths = [];
            foreach ($request->file('attachment') as $file) {
                $extension = $file->getClientOriginalExtension();
                $filename = 'helpdesk-' . $ticket->ticket_number . '-' . now()->format('YmdHis') . '-' . uniqid() . '.' . $extension;
                $path = $file->storeAs('images/helpdesk', $filename, 'public');
                $paths[] = $path;
            }
            $ticket->attachment = $paths;
        }

        $ticket->save();

        $itUsers = User::where('unit', 'Teknologi Informasi')->get();
        foreach ($itUsers as $user) {
            $user->notify(new NewTicketNotification($ticket));
        }

        return redirect()->route('helpdesk.index')
            ->with('success', 'Tiket berhasil dibuat. Nomor tiket: ' . $ticket->ticket_number);
    }

    public function show($id)
    {
        $ticket = Ticket::with(['approval', 'updates.user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        return view('pages.helpdesk.show', compact('ticket'));
    }
}
