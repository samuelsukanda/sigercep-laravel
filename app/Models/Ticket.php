<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Binafy\LaravelUserMonitoring\Traits\Actionable;
use Carbon\Carbon;

class Ticket extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['ticket_number', 'user_id', 'category', 'description', 'penanganan', 'urgency', 'attachment', 'status'];

    protected $casts = [
        'created_at' => 'datetime',
        'resolved_at' => 'datetime',
        'attachment' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approval()
    {
        return $this->hasOne(TicketApproval::class);
    }

    public function updates()
    {
        return $this->hasMany(TicketUpdate::class);
    }

    public function scopeFilter($query, $request)
    {
        $start = $request->filled('periode_dari')
            ? Carbon::createFromFormat('d-m-Y', $request->periode_dari)->startOfDay()
            : now()->startOfMonth();

        $end = $request->filled('periode_sampai')
            ? Carbon::createFromFormat('d-m-Y', $request->periode_sampai)->endOfDay()
            : now()->endOfMonth();

        $query->whereBetween('created_at', [$start, $end]);

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

        return $query;
    }
}
