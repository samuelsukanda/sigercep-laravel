<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HardwareHealthCheck extends Model
{
    use HasFactory;

    protected $table = 'hardware_health_checks';

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