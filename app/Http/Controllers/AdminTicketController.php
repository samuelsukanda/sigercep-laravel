<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketApproval;
use App\Models\TicketUpdate;
use App\Notifications\TicketApprovalNotification;
use App\Notifications\TicketStatusUpdatedNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AdminTicketController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $columns = ['ticket_number', 'user.name', 'user.unit', 'created_at', 'category', 'urgency', 'status', 'approval.approval_status'];

            $query = Ticket::with(['user', 'approval']);

            if ($request->filled('periode_dari')) {
                try {
                    $startDate = Carbon::createFromFormat('d-m-Y', $request->periode_dari)->startOfDay();
                    $query->where('created_at', '>=', $startDate);
                } catch (\Exception $e) {
                }
            }

            if ($request->filled('periode_sampai')) {
                try {
                    $endDate = Carbon::createFromFormat('d-m-Y', $request->periode_sampai)->endOfDay();
                    $query->where('created_at', '<=', $endDate);
                } catch (\Exception $e) {
                }
            }

            if ($request->filled('kategori')) {
                $query->where('category', $request->kategori);
            }
            if ($request->filled('urgency')) {
                $query->where('urgency', $request->urgency);
            }
            if ($request->filled('status_tiket')) {
                $query->where('status', $request->status_tiket);
            }
            if ($request->filled('status_approval')) {
                if ($request->status_approval == 'Pending') {
                    $query->whereDoesntHave('approval');
                } else {
                    $query->whereHas('approval', function ($q) use ($request) {
                        $q->where('approval_status', $request->status_approval);
                    });
                }
            }

            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];

                $query->where(function ($q) use ($search) {
                    $q->where('ticket_number', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%")
                        ->orWhere('urgency', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($qUser) use ($search) {
                            $qUser->where('name', 'like', "%{$search}%")
                                ->orWhere('unit', 'like', "%{$search}%");
                        });
                });
            }

            $recordsTotal = Ticket::count();
            $recordsFiltered = $query->count();

            if ($request->has('order')) {
                $colIdx = $request->order[0]['column'];
                $orderDir = $request->order[0]['dir'] ?? 'desc';

                if ($colIdx == 1) {
                    $query->join('users', 'tickets.user_id', '=', 'users.id')
                        ->orderBy('users.name', $orderDir)
                        ->select('tickets.*');
                } elseif ($colIdx == 2) {
                    $query->join('users', 'tickets.user_id', '=', 'users.id')
                        ->orderBy('users.unit', $orderDir)
                        ->select('tickets.*');
                } elseif ($colIdx == 7) {
                    $query->leftJoin('ticket_approvals', 'tickets.id', '=', 'ticket_approvals.ticket_id')
                        ->orderBy('ticket_approvals.approval_status', $orderDir)
                        ->select('tickets.*');
                } else {
                    $orderColumn = $columns[$colIdx] ?? 'created_at';
                    $query->orderBy($orderColumn, $orderDir);
                }
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $start = $request->start ?? 0;
            $length = $request->length ?? 10;

            $records = $query->skip($start)->take($length)->get();

            $data = [];

            foreach ($records as $item) {
                $userName = ucwords(str_replace('.', ' ', $item->user->name ?? '-'));
                $userUnit = $item->user->unit ?? '-';
                $createdTimestamp = Carbon::parse($item->created_at)->timestamp;
                $createdFormatted = Carbon::parse($item->created_at)->translatedFormat('d F Y H:i');

                $urgencyBadge = view('components.badge.urgency-badge', ['urgency' => $item->urgency])->render();
                $statusBadge = view('components.badge.status-badge', ['status' => $item->status])->render();
                $approvalBadge = view('components.badge.status-approval-badge', ['status' => $item->approval?->approval_status ?? 'Pending'])->render();

                $data[] = [
                    'id' => $item->id,
                    'ticket_number' => $item->ticket_number,
                    'user_name' => $userName,
                    'user_unit' => $userUnit,
                    'created_at_timestamp' => $createdTimestamp,
                    'created_at_formatted' => $createdFormatted,
                    'category' => $item->category ?? '-',
                    'urgency_badge' => $urgencyBadge,
                    'status_badge' => $statusBadge,
                    'approval_badge' => $approvalBadge,
                    'can_update' => \App\Helpers\PermissionHelper::canAccess('helpdesk', 'manage'),
                    'can_delete' => \App\Helpers\PermissionHelper::canAccess('helpdesk', 'manage'),
                ];
            }

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data
            ]);
        }

        return view('pages.helpdesk.admin.index');
    }

    public function edit(Ticket $helpdesk)
    {
        $ticket = $helpdesk;
        return view('pages.helpdesk.admin.edit', compact('ticket'));
    }

    public function show($id)
    {
        $ticket = Ticket::with(['user', 'approval', 'updates.user'])->findOrFail($id);
        return view('pages.helpdesk.admin.show', compact('ticket'));
    }

    public function update(Request $request, Ticket $helpdesk)
    {
        $request->validate([
            'category' => 'required',
            'description' => 'required',
            'urgency' => 'required',
        ]);

        $helpdesk->update($request->only('category', 'description', 'urgency', 'unit_name'));

        return redirect(route('admin.helpdesk.index') . '?deleted=1')->with('success', 'Tiket diperbarui.');
    }

    public function destroy(Ticket $helpdesk)
    {
        if ($helpdesk->approval) {
            $helpdesk->approval->forceDelete();
        }
        $helpdesk->updates()->forceDelete();

        $deleted = $helpdesk->forceDelete();
        if (!$deleted) {
            dd('Gagal delete, kemungkinan ada error');
        }
        return redirect(route('admin.helpdesk.index') . '?deleted=1')->with('success', 'Tiket berhasil dihapus.');
    }

    public function approve(Request $request, Ticket $ticket)
    {
        $request->validate([
            'analysis' => 'required',
            'action_plan' => 'required',
            'estimated_completion' => 'nullable|date',
            'category'   => 'required|in:Hardware,Printer,Jaringan,Software,SIMRS',
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
        } else {
            $ticket->save();
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

        // ⬅️ Jika sebelumnya Open & mulai dikerjakan
        if ($ticket->status == 'Open' && $request->status == 'In Progress') {
            $ticket->status = 'In Progress';
        } else {
            $ticket->status = $request->status;
        }

        // ⬅️ Jika selesai
        if ($request->status == 'Done') {
            $ticket->resolved_at = now();

            // Update approval jika sebelumnya Need Clarification
            $approval = $ticket->approval;
            if ($approval && $approval->approval_status == 'Need Clarification') {
                $approval->approval_status = 'Approved';
                $approval->approved_at = now();
                $approval->approved_by = Auth::user()->name;
                $approval->save();
            }
        }

        if ($request->status == 'Closed') {
            $ticket->resolved_at = now();
        }

        $ticket->save();

        $update = TicketUpdate::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'status' => $ticket->status,
            'note' => $request->note
        ]);

        if ($ticket->user) {
            $ticket->user->notify(new TicketStatusUpdatedNotification($update));
        }

        return back()->with('success', 'Status tiket diperbarui.');
    }
}
