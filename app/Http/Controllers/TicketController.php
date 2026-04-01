<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Helpers\TicketHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\NewTicketNotification;

class TicketController extends Controller
{

    public function create()
    {
        return view('pages.helpdesk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string',
            'unit_name'  => 'required|string',
            'category'   => 'nullable|in:Hardware,Printer,Jaringan,Software,SIMRS',
            'description' => 'required|string',
            'urgency'    => 'nullable|in:Low,Medium,High,Critical',
            'attachment' => 'nullable|array',
            'attachment.*' => 'file|mimes:jpg,png,jpeg,doc,docx,xls,xlsx,pdf|max:2048'
        ], [
            'attachment.max' => 'Ukuran file terlalu besar! Maksimal 2 MB.',
            'attachment.mimes' => 'Format file harus jpg, png, jpeg, doc, docx, xls, xlsx, atau pdf.'
        ]);

        $ticket = new Ticket();
        $ticket->ticket_number = TicketHelper::generateTicketNumber();
        $ticket->user_id       = Auth::id();
        $ticket->name          = Auth::user()->name;
        $ticket->unit_name     = $request->unit_name;
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

            // simpan ke DB (json)
            $ticket->attachment = $paths;
        }

        $ticket->save();

        $itUsers = User::where('unit', 'Teknologi Informasi')->get();

        if ($itUsers->isEmpty()) {
            logger()->warning('Tidak ada IT untuk menerima notifikasi.');
        }

        foreach ($itUsers as $user) {
            $user->notify(new NewTicketNotification($ticket));
        }

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
