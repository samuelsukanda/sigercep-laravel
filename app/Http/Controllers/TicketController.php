<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Helpers\TicketHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{

    public function create()
    {
        return view('pages.helpdesk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit'       => 'required|string',
            'category'   => 'required|in:Hardware,Printer,Jaringan,Software,SIMRS',
            'description' => 'required|string|min:5',
            'urgency'    => 'required|in:Low,Medium,High,Critical',
            'attachment' => 'nullable|file|mimes:jpg,png,jpeg,doc,docx,xls,xlsx,pdf|max:2048'
        ]);

        $ticket = new Ticket();
        $ticket->ticket_number = TicketHelper::generateTicketNumber();
        $ticket->user_id       = Auth::id();
        $ticket->unit          = $request->unit;
        $ticket->category      = $request->category;
        $ticket->description   = $request->description;
        $ticket->urgency       = $request->urgency;
        $ticket->status        = 'Open';

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $extension = $file->getClientOriginalExtension();
            $filename = 'helpdesk-' . $ticket->ticket_number . '-' . Carbon::now()->format('YmdHis') . '.' . $extension;
            $path = $file->storeAs('images/helpdesk', $filename, 'public');
            $ticket->attachment = $path;
        }

        $ticket->save();

        return redirect()->route('helpdesk.index')
            ->with('success', 'Tiket berhasil dibuat. Nomor tiket: ' . $ticket->ticket_number);
    }

    public function index()
    {
        $tickets = Ticket::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('pages.helpdesk.index', compact('tickets'));
    }

    public function show($id)
    {
        $ticket = Ticket::with(['approval', 'updates.user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        return view('pages.helpdesk.show', compact('ticket'));
    }
}
