<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ManajemenRisiko extends Model
{
    use HasFactory;

    protected $table = 'manajemen_risiko';

    protected $fillable = [
        'nama',
        'unit',
        'tanggal',
        'uraian',
        'dampak',
        'kemungkinan',
        'nilai',
        'keterangan',
    ];
}
