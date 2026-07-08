<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaftarRisiko extends Model
{
    use HasFactory;

    protected $table = 'daftar_risikos';

    protected $fillable = [
        'unit',
        'no_urut',
        'risiko',
        'kode_risiko',
        'sebab',
        'sumber_risiko',
        'c_uc',
        'dampak',
        'pengendalian',
        'efektif',
        'tidak_efektif',
        'analisis_p',
        'analisis_d',
        'analisis_bobot',
        'analisis_nilai',
        'analisis_tingkat',
        'target_waktu',
        'mitigasi_p',
        'mitigasi_d',
        'mitigasi_bobot',
        'mitigasi_nilai',
        'mitigasi_tingkat',
    ];

    protected $casts = [
        'efektif' => 'boolean',
        'tidak_efektif' => 'boolean',
        'analisis_p' => 'decimal:2',
        'analisis_d' => 'decimal:2',
        'analisis_bobot' => 'decimal:2',
        'analisis_nilai' => 'decimal:2',
        'mitigasi_p' => 'decimal:2',
        'mitigasi_d' => 'decimal:2',
        'mitigasi_bobot' => 'decimal:2',
        'mitigasi_nilai' => 'decimal:2',
    ];
}
