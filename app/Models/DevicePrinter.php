<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DevicePrinter extends Model
{
    protected $fillable = [
        'ip_pc',
        'nama_perangkat',
        'jenis',
        'merk_type',
        'kondisi',
        'keterangan',
        'foto',
    ];
}
