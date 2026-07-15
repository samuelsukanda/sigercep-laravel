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
        'mitigasi_tw1_p',
        'mitigasi_tw1_d',
        'mitigasi_tw1_bobot',
        'mitigasi_tw1_nilai',
        'mitigasi_tw1_tingkat',
        'mitigasi_tw2_p',
        'mitigasi_tw2_d',
        'mitigasi_tw2_bobot',
        'mitigasi_tw2_nilai',
        'mitigasi_tw2_tingkat',
        'mitigasi_tw3_p',
        'mitigasi_tw3_d',
        'mitigasi_tw3_bobot',
        'mitigasi_tw3_nilai',
        'mitigasi_tw3_tingkat',
        'mitigasi_tw4_p',
        'mitigasi_tw4_d',
        'mitigasi_tw4_bobot',
        'mitigasi_tw4_nilai',
        'mitigasi_tw4_tingkat',
    ];

    protected $casts = [
        'efektif' => 'boolean',
        'tidak_efektif' => 'boolean',
        'analisis_p' => 'decimal:2',
        'analisis_d' => 'decimal:2',
        'analisis_bobot' => 'decimal:2',
        'analisis_nilai' => 'decimal:2',
        'mitigasi_tw1_p' => 'decimal:2',
        'mitigasi_tw1_d' => 'decimal:2',
        'mitigasi_tw1_bobot' => 'decimal:2',
        'mitigasi_tw1_nilai' => 'decimal:2',
        'mitigasi_tw2_p' => 'decimal:2',
        'mitigasi_tw2_d' => 'decimal:2',
        'mitigasi_tw2_bobot' => 'decimal:2',
        'mitigasi_tw2_nilai' => 'decimal:2',
        'mitigasi_tw3_p' => 'decimal:2',
        'mitigasi_tw3_d' => 'decimal:2',
        'mitigasi_tw3_bobot' => 'decimal:2',
        'mitigasi_tw3_nilai' => 'decimal:2',
        'mitigasi_tw4_p' => 'decimal:2',
        'mitigasi_tw4_d' => 'decimal:2',
        'mitigasi_tw4_bobot' => 'decimal:2',
        'mitigasi_tw4_nilai' => 'decimal:2',
    ];
}
