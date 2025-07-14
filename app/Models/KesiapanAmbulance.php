<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KesiapanAmbulance extends Model
{
    use HasFactory;

    protected $table = 'kesiapan_ambulance';

    protected $fillable = [
        'mobil_ambulance',
        'tanggal',
        'perawat',
        'kondisi_mobil',
        'kondisi_driver',
        'oksigen',
        'regulator_oksigen',
        'kebersihan',
        'monitor_pasien',
        'aed',
        'suction',
        'ventilator',
        'bed_pasien',
        'linen',
        'obat',
        'inverter',
    ];
}
