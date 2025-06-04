<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = [
        'username',
        'name',
        'password',
        'level',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        //'email_verified_at' => 'datetime', // ini bisa dihapus kalau kamu gak pakai email
        'password' => 'hashed',
    ];
}
