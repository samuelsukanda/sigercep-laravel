<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'unit',
        'role',
        'nik',
        'jabatan',
        'status_karyawan',
        'last_seen_at', 
    ];

    protected $hidden = [
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function approvals()
    {
        return $this->hasMany(TicketApproval::class, 'admin_id');
    }
}
