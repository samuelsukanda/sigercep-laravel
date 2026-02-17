<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketApproval extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['ticket_id', 'admin_id', 'analysis', 'action_plan', 'estimated_completion', 'approval_status', 'approval_note', 'approved_at', 'approved_by'];

    protected $casts = [
        'estimated_completion' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function getDurationAttribute()
    {
        if (!$this->approved_at || !$this->estimated_completion) {
            return '-';
        }

        $diff = $this->approved_at->diff($this->estimated_completion);

        $duration = [];

        if ($diff->d > 0) $duration[] = $diff->d . ' hari';
        if ($diff->h > 0) $duration[] = $diff->h . ' jam';
        if ($diff->i > 0) $duration[] = $diff->i . ' menit';

        return implode(' ', $duration) ?: 'Kurang dari 1 menit';
    }
}
