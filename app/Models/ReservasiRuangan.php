<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservasiRuangan extends Model
{
    use HasFactory;

    protected $table = 'reservasi_ruangan';

    protected $fillable = [
        'nama',
        'unit',
        'jam_mulai',
        'jam_selesai',
        'tanggal',
        'ruang',
        'approval',
    ];
}
