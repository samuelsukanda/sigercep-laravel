<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HardwareCredential extends Model
{
    use HasFactory;

    protected $table = 'hardware_credentials';

    protected $fillable = [
        'nama_pc',
        'ip',
        'unit',
        'lantai',
        'items',
    ];

    protected $casts = [
        'items' => 'array',
    ];
}