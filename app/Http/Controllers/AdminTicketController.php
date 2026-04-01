<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketApproval;
use App\Models\TicketUpdate;
use App\Notifications\TicketApprovalNotification;
use App\Notifications\TicketStatusUpdatedNotification;
use Illuminate\Support\Facades\Auth;

class AdminTicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['user', 'approval']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('approval_status')) {
            $query->whereHas('approval', function ($q) use ($request) {
                $q->where('approval_status', $request->approval_status);
            });
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(15);
        return view('pages.helpdesk.admin.index', compact('tickets'));
    }

    public function edit(Ticket $ticket)
    {
        return view('pages.helpdesk.admin.edit', compact('ticket'));
    }

    public function show($id)
    {
        $ticket = Ticket::with(['user', 'approval', 'updates.user'])->findOrFail($id);
        return view('pages.helpdesk.admin.show', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'category' => 'required',
            'description' => 'required',
            'urgency' => 'required',
        ]);

        $ticket->update($request->only('category', 'description', 'urgency', 'unit_name'));

        return redirect()->route('admin.helpdesk.index')->with('success', 'Tiket diperbarui.');
    }

    public function destroy(Ticket $ticket)
    {
        $deleted = $ticket->forceDelete();
        if (!$deleted) {
            dd('Gagal delete, kemungkinan ada error');
        }
        return redirect()->route('admin.helpdesk.index')->with('success', 'Tiket berhasil dihapus.');
    }

    public function approve(Request $request, Ticket $ticket)
    {
        $request->validate([
            'analysis' => 'required',
            'action_plan' => 'required',
            'estimated_completion' => 'nullable|date',
            'category'   => 'required|in:Hardware,Jaringan,Software,SIMRS',
            'urgency'    => 'required|in:Low,Medium,High,Critical',
            'approval_status' => 'required|in:Approved,Rejected,Need Clarification',
            'approval_note' => 'required_if:approval_status,Rejected,Need Clarification'
        ]);

        $ticket->update([
            'category' => $request->category,
            'urgency' => $request->urgency,
        ]);

        $approval = new TicketApproval();
        $approval->ticket_id = $ticket->id;
        $approval->admin_id = Auth::id();
        $approval->analysis = $request->analysis;
        $approval->action_plan = $request->action_plan;
        $approval->estimated_completion = $request->estimated_completion;
        $approval->approval_status = $request->approval_status;
        $approval->approval_note = $request->approval_note;
        $approval->approved_at = now();
        $approval->approved_by = Auth::user()->name;

        $approval->save();

        if ($request->approval_status == 'Approved') {
            $ticket->status = 'Done';
        } elseif ($request->approval_status == 'Rejected') {
            $ticket->status = 'Closed';
        }

        if (in_array($request->approval_status, ['Approved', 'Rejected'])) {
            $ticket->resolved_at = now();
            $ticket->save();

            TicketUpdate::create([
                'ticket_id' => $ticket->id,
                'user_id' => Auth::id(),
                'status' => $ticket->status,
            ]);
        }

        if ($ticket->user) {
            $ticket->user->notify(new TicketApprovalNotification($ticket, $approval));
        }

        return redirect()->back()->with('success', 'Approval berhasil disimpan.');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:In Progress,Closed,Done',
            'note' => 'required'
        ]);

        $ticket->status = $request->status;

        if (in_array($request->status, ['Closed', 'Done'])) {
            $ticket->resolved_at = now();
        }

        $ticket->save();

        $update = TicketUpdate::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'status' => $request->status,
            'note' => $request->note
        ]);

        $ticket->user->notify(new TicketStatusUpdatedNotification($update));

        return back()->with('success', 'Status tiket diperbarui.');
    }
}
