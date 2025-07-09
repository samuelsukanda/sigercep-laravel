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
        'unit',
        'keperluan',
        'tanggal',
        'nama_barang',
        'tempat_asal_barang',
        'foto_barang',
        'foto_barcode',
    ];
}
