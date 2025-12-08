<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemindahanAset extends Model
{
    use HasFactory;

    protected $table = 'pemindahan_aset';

    protected $fillable = [
        'nama',
        'unit_asal',
        'unit_tujuan',
        'keperluan',
        'tanggal',
        'nama_barang',
        'foto_barang',
        'foto_barcode',
    ];
}
