<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DesainGrafis extends Model
{
    use HasFactory;

    protected $table = 'desain_grafis';

    protected $fillable = [
        'nama',
        'unit',
        'keperluan',
        'tanggal',
        'desain',
        'status',
        'panjang',
        'tinggi',
        'satuan',
        'menit',
        'detik',
    ];
}
