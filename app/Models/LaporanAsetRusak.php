<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanAsetRusak extends Model
{
    use HasFactory;

    protected $table = 'laporan_aset_rusak';

    protected $fillable = [
        'nama',
        'unit',
        'nama_aset',
        'lokasi_aset',
        'kondisi_aset',
        'tanggal',
        'status',
        'foto',
        'foto_barcode',
    ];
}
