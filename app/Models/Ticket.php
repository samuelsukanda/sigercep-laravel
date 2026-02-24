<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['ticket_number', 'user_id', 'unit_name', 'category', 'description', 'urgency', 'attachment', 'status'];

    protected $casts = [
        'created_at' => 'datetime',
        'resolved_at' => 'datetime',
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
}
