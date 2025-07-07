<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KecelakaanKerja extends Model
{
    use HasFactory;

    protected $table = 'kecelakaan_kerja';

    protected $fillable = [
        'nama',
        'unit',
        'no_hp',
        'jam',
        'tanggal',
        'jenis_kecelakaan',
        'lokasi_kecelakaan',
        'saksi',
        'kegiatan',
        'riwayat',
        'penyebab',
        'bahan',
        'cedera',
        'pengobatan',
        'pengobatan2',
        'pelaksana',
        'tanda_tangan',
    ];
}
