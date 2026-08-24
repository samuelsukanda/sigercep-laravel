<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Helpers\TicketHelper;
use App\Helpers\TelegramHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\NewTicketNotification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $columns = ['ticket_number', 'user.name', 'user.unit', 'created_at', 'category', 'urgency', 'status', 'approval.approval_status'];

            $query = Ticket::with(['user', 'approval'])->where('user_id', Auth::id());

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
                        ->orWhere('status', 'like', "%{$search}%");
                });
            }

            $recordsTotal = Ticket::where('user_id', Auth::id())->count();
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
                ];
            }

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data
            ]);
        }

        $isFiltered = $request->hasAny([
            'periode_dari',
            'periode_sampai',
            'kategori',
            'urgency',
            'status_tiket',
            'status_approval'
        ]);

        return view('pages.helpdesk.index', compact('isFiltered'));
    }

    public function create(Request $request)
    {
        $fromKb    = $request->boolean('from_kb');
        $kbTitle   = $fromKb ? $request->input('kb_title', '') : null;

        return view('pages.helpdesk.create', compact('fromKb', 'kbTitle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category'    => 'nullable|in:Hardware,Printer,Jaringan,Software,SIMRS',
            'description' => 'required|string',
            'penanganan'  => 'nullable|string',
            'urgency'     => 'nullable|in:Low,Medium,High,Critical',
            'attachment'  => 'nullable|array',
            'attachment.*' => 'file|mimes:jpg,png,jpeg,doc,docx,xls,xlsx,pdf|max:2048'
        ]);

        $normalizedDescription = strtolower(
            trim(preg_replace('/\s+/', ' ', $request->description))
        );

        $fingerprint = md5(
            Auth::id() . '|' .
                $normalizedDescription . '|' .
                $request->category . '|' .
                now()->format('Y-m-d H:i')
        );

        try {
            DB::beginTransaction();

            $exists = Ticket::where('fingerprint', $fingerprint)->exists();
            if ($exists) {
                DB::rollBack();
                return back()->withInput()
                    ->with('error', 'Tiket yang sama sudah dibuat di waktu yang sama.');
            }

            $ticket = new Ticket();
            $ticket->ticket_number = TicketHelper::generateTicketNumber();
            $ticket->user_id       = Auth::id();
            $ticket->category      = $request->category;
            $ticket->description   = $request->description;
            $ticket->penanganan    = $request->filled('penanganan') ? $request->penanganan : null;
            $ticket->urgency       = $request->urgency;
            $ticket->status        = 'Open';
            $ticket->fingerprint   = $fingerprint;

            if ($request->hasFile('attachment')) {
                $paths = [];

                foreach ($request->file('attachment') as $file) {
                    $filename = 'helpdesk-' . now()->format('YmdHis') . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $paths[] = $file->storeAs('images/helpdesk', $filename, 'public');
                }

                $ticket->attachment = $paths;
            }

            $ticket->save();

            DB::commit();

            try {
                $desc = e(Str::limit($ticket->description, 500));
                $userData = Auth::user();
                $name = ucwords(str_replace('.', ' ', $userData->name ?? '-'));
                $unit = e($userData->unit ?? '-');
                $jabatan = e($userData->jabatan ?? '-');

                $message = "<b>📌 Tiket Baru</b>\n\n"
                    . "<b>No:</b> {$ticket->ticket_number}\n"
                    . "<b>Nama:</b> {$name}\n"
                    . "<b>Divisi:</b> {$unit}\n"
                     . "<b>Jabatan:</b> {$jabatan}\n\n"
                    . "<b>Deskripsi:</b>\n{$desc}";

                $response = TelegramHelper::send($message);

                if (!$response || !$response->ok()) {
                    Log::error('Telegram gagal', [
                        'response' => $response ? $response->body() : 'null'
                    ]);
                }

                if (!empty($ticket->attachment)) {
                    foreach ($ticket->attachment as $file) {

                        $filePath = storage_path('app/public/' . $file);

                        if (file_exists($filePath)) {
                            $res = Http::attach(
                                'document',
                                file_get_contents($filePath),
                                basename($filePath)
                            )->post("https://api.telegram.org/bot" . config('services.telegram.token') . "/sendDocument", [
                                'chat_id' => config('services.telegram.chat_id'),
                            ]);

                            if (!$res->ok()) {
                                Log::error('Telegram attachment gagal', [
                                    'file' => $file,
                                    'response' => $res->body()
                                ]);
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error('Telegram Exception', [
                    'message' => $e->getMessage()
                ]);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            if ($e->getCode() == 23000) {
                return back()->withInput()
                    ->with('error', 'Tiket duplikat terdeteksi (double submit).');
            }

            throw $e;
        }

        $itUsers = User::where('unit', 'Teknologi dan Informasi')->get();

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
