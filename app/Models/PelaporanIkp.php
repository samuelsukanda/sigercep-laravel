<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PelaporanIkp extends Model
{
    use HasFactory;

    protected $table = 'pelaporan_ikp';

    protected $fillable = [
        'nama',
        'no_rm',
        'tanggal_lahir',
        'kelompok_umur',
        'jenis_kelamin',
        'penanggung_jawab',
        'tanggal_masuk_rs',
        'rincian_kejadian',
        'tanggal_kejadian',
        'waktu_kejadian',
        'insiden',
        'kronologis_kejadian',
        'jenis_kejadian',
        'orang_pelapor',
        'jenis_insiden',
        'insiden_pasien',
        'lokasi_insiden',
        'jenis_spesialisasi_pasien',
        'unit_terkait',
        'akibat_insiden',
        'tindakan_yang_dilakukan',
        'tindakan_dilakukan_oleh',
        'kejadian_serupa',
        'grading_risiko',
    ];
}
