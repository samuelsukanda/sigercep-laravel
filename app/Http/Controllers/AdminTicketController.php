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
            'estimated_completion' => 'required|date|after_or_equal:now',
            'approval_status' => 'required|in:Approved,Rejected,Need Clarification',
            'approval_note' => 'required_if:approval_status,Rejected,Need Clarification'
        ], [
            'estimated_completion.after_or_equal' => 'Estimasi penyelesaian tidak boleh kurang dari waktu approval.'
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

        if (in_array($request->approval_status, ['Rejected', 'Need Clarification'])) {
            $ticket->status = 'Closed';
            $ticket->resolved_at = now();
            $ticket->save();

            TicketUpdate::create([
                'ticket_id' => $ticket->id,
                'user_id' => Auth::id(),
                'status' => 'Closed',
                'note' => 'Tiket ditutup otomatis karena status approval: ' . $request->approval_status . '. Catatan: ' . ($request->approval_note ?? ''),
            ]);
        }

        $ticket->user->notify(new TicketApprovalNotification($ticket, $approval));

        return redirect()->back()->with('success', 'Approval berhasil disimpan.');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:In Progress,Closed',
            'note' => 'required'
        ]);

        $ticket->status = $request->status;

        if ($request->status == 'Closed') {
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
