<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservasiKendaraan extends Model
{
    use HasFactory;

    protected $table = 'reservasi_kendaraan';

    protected $fillable = [
        'nama',
        'unit',
        'tempat_tujuan',
        'keperluan',
        'jam_berangkat',
        'jam_pulang',
        'tanggal',
        'jenis_kendaraan',
        'jumlah_penumpang',
        'waktu_tempuh',
        'jarak_tempuh',
        'jenis_layanan',
    ];
}
