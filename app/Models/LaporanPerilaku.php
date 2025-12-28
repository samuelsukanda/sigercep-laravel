<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LaporanPerilaku extends Model
{
    use HasFactory;

    protected $table = 'laporan_perilaku';

    protected $fillable = [
        'nama',
        'nik',
        'unit',
        'tanggal',
        'kategori_laporan',
        'keterangan_perilaku',
        'file_pdf',
        'file_path',
    ];
}
