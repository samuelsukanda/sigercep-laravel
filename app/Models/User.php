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
        'unit_id',
        'jabatan_id',
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

    /* Nama tampilan untuk dropdown: "raden.ibnu" -> "Raden Ibnu", "sammuel" -> "Sammuel". Nama yang sudah pakai huruf kapital dibiarkan. */
    public function getDisplayNameAttribute()
    {
        $name = trim($this->name ?? '');
        if ($name === '') return $this->name;
        if ($name !== strtolower($name)) return $this->name;
        return ucwords(str_replace('.', ' ', strtolower($name)));
    }
}
