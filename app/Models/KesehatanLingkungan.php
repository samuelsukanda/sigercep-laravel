<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KesehatanLingkungan extends Model
{
    use HasFactory;

    protected $table = 'kesehatan_lingkungan';

    protected $fillable = [
        'nama',
        'unit',
        'tanggal',
        'lokasi_masalah',
        'jenis_hama',
        'dokumentasi',
    ];
}
